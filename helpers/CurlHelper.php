<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CurlHelper' ) ) {

	class CurlHelper {

		const AGENT_MOZILLA = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.7.12) Gecko/20050919 Firefox/1.0.7';
		const AGENT_OPERA   = 'Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14';

		public $clear_before_execute = true;
		public $url;

		/**
		 * Флаг вывода дополнительной информации, записывает поток в stderr или файл $stderr
		 *
		 * @var bool
		 */
		public $verbose;

		/**
		 * Содержимое заголовка  "Accept-Encoding:". Позволяет декодировать запрос.
		 * Допустимые значения  "identity", "deflate", "gzip"
		 *
		 * @var string
		 */
		public $encoding;

		/**
		 * Включать или нет заголовки в вывод
		 *
		 * @var bool
		 */
		public $header;

		/**
		 * Флаг отслеживания строки запроса дескриптора
		 *
		 * @var bool
		 */
		public $headerOut;

		/**
		 * Флаг возврата резальтата передачи вместо вывода прямо в браузер
		 *
		 * @var bool
		 */
		public $returnTransfer = true;

		/**
		 * Количество секунд ожидания при попытке соединения. 0 - бесконечное ожидание
		 *
		 * @var int
		 */
		public $connectTimeout;

		/**
		 * Количество секунд для выполнения операции (curl запроса)
		 *
		 * @var int
		 */
		public $timeout;

		/**
		 * Массив HTTP-заголовков
		 *
		 * @var string[]
		 */
		public $httpheader;

		/**
		 * Флаг проверки ssl сертификата узла
		 *
		 * @var bool
		 */
		public $ssl_verifypeer = 0;

		/**
		 * Содержимое заголовка "User-Agent: "
		 *
		 * @var string
		 */
		public $useragent;

		/**
		 *  Имя файла для хранения cookies текущего запроса
		 *
		 * @var string
		 *
		 */
		public $cookiejar;

		/**
		 * Имя файла для получения cookies
		 *
		 * @var string
		 */
		public $cookiefile;

		/**
		 * Данные для передачи методом POST
		 *
		 * @var string | array
		 */
		public $postfields;

		/**
		 * Признак POST запроса
		 *
		 * @var true
		 */
		public $post;

		/**
		 * TRUE для подробного отчета при неудаче, если полученный HTTP-код
		 * больше или равен 400. Поведение по умолчанию возвращает страницу
		 * как обычно, игнорируя код.
		 *
		 * @var bool
		 */
		public $failonerror;
		public $file;

		/**
		 * Используйте 1 для проверки существования общего имени в сертификате SSL.
		 * Используйте 2 для проверки существования общего имени и также его
		 * совпадения с указанным хостом. В боевом окружении значение этого
		 * параметра должно быть 2 (установлено по умолчанию).
		 *
		 * @var int
		 */
		public $ssl_verifyhost = 0;
		public $cookiesession;

		/**
		 * Флаг, переходить ли по редиректам
		 *
		 * @var bool
		 */
		public $followlocation;

		/**
		 * Максимально число переходов
		 *
		 * @var int
		 */
		public $maxredirs;
		private $_curl;
		private $_body;
		private $_info;
		private $_errno;
		private $_error;
		private $_timeExecute;
		private static $_names = array();

		public function execute() {

			$this->initCurl();
			$startTime = microtime( true );
			$this->_body = curl_exec( $this->_curl );
			$this->_timeExecute = microtime( true ) - $startTime;
			$this->_info = curl_getinfo( $this->_curl );
			if ( $this->timeout && $this->_timeExecute > $this->timeout ) {
				$this->_info['http_code'] = 503;
			}
			$this->_errno = curl_errno( $this->_curl );
			$this->_error = curl_error( $this->_curl );

			return $this->is200();
		}

		public function getTimeExecute() {
			return $this->_timeExecute;
		}

		public function getBody() {
			if ( $this->header ) {
				return $this->_body;
			} else {
				return $this->getResponseBody();
			}
		}

		public function getResponseBody() {
			$body = str_replace( "\r", '', $this->_body );
			$response = explode( "\n\n", $body );
			array_shift( $response );
			$body = implode( "\n\n", $response );
			return $body;
		}

		public function getResponseHeader() {
			$body = str_replace( "\r", '', $this->_body );
			$response = explode( "\n\n", $body );
			return $response[0] . "\n";
		}

		public function getHttpCode() {
			return $this->_info['http_code'];
		}

		public function getCurlErrno() {
			return $this->_errno;
		}

		public function getCurlInfo() {
			return $this->_info;
		}

		public function getCurlError() {
			return $this->_error;
		}

		public function is200() {
			return $this->getHttpCode() == 200;
		}

		public function getRedirectUrl() {
			if ( $this->getHttpCode() !== 301 && $this->getHttpCode() !== 302 ) {
				return '';
			}
			$matches = array();
			preg_match( '/Location:(.*?)\n/', $this->getResponseHeader(), $matches );
			if ( ! empty( $matches[1] ) ) {
				return $matches[1];
			} else {
				return $this->_info['redirect_url'];
			}
		}

		private function initCurl() {
			if ( empty( $this->_curl ) || $this->clear_before_execute ) {
				$this->_curl = curl_init();
			}
			// curl_reset( $this->_curl );
			$this->setCurlOpt();
			if ( ! is_null( $this->headerOut ) ) {
				curl_setopt( $this->_curl, CURLINFO_HEADER_OUT, $this->headerOut );
			}
		}

		private function setCurlOpt() {
			foreach ( $this->attributeNames() as $attribute ) {
				$curlOpt = 'CURLOPT_' . mb_strtoupper( $attribute );
				$value = $this->$attribute;
				if ( $attribute == 'header' ) {
					$value = true;
				}
				if ( defined( $curlOpt ) && ! is_null( $value ) ) {

					curl_setopt( $this->_curl, constant( $curlOpt ), $value );
				}
			}
		}

		public function getEffectiveUrl() {
			return curl_getinfo( $this->_curl, CURLINFO_EFFECTIVE_URL );
		}

		public function getInfo() {
			return curl_getinfo( $this->_curl );
		}

		public function __destruct() {
			if ( ! empty( $this->_curl ) ) {
				curl_close( $this->_curl );
			}
		}

		public function attributeNames() {
			$className = get_class( $this );
			if ( ! isset( self::$_names[ $className ] ) ) {
				$class = new ReflectionClass( get_class( $this ) );
				$names = array();
				foreach ( $class->getProperties() as $property ) {
					$name = $property->getName();
					if ( $property->isPublic() && ! $property->isStatic() )
						$names[] = $name;
				}
				return self::$_names[ $className ] = $names;
			} else {
				return self::$_names[ $className ];
			}
		}

	}

}
