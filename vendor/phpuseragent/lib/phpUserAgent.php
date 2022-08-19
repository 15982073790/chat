<?php

/**
 * Simple PHP User agent
 *
 * @link      http://github.com/ornicar/php-user-agent
 * @version   1.0
 * @author    Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @license   MIT License
 *
 * Documentation: http://github.com/ornicar/php-user-agent/blob/master/README.markdown
 * Tickets:       http://github.com/ornicar/php-user-agent/issues
 */
class phpUserAgent
{
  protected $userAgentString;
  protected $browserName;
  protected $browserVersion;
  protected $operatingSystem;
  protected $engine;

  public function __construct($userAgentString = null, \phpUserAgentStringParser $userAgentStringParser = null)
  {
    $this->configureFromUserAgentString($userAgentString, $userAgentStringParser);
  }

  /**
   * Get the browser name
   *
   * @return string the browser name
   */
  public function getBrowserName()
  {
    return $this->browserName;
  }
  //获取中文版浏览器名字
    public function getBrowserNameCn()
    {
        $BrowserNameCn=array(
            'shiretoko'     => '火狐浏览器',
            'qqbrowser'     => 'QQ浏览器',
            'qqnbrowser'     => 'QQ内浏览器',
            'opr'     => '欧朋浏览器',
            'safari'     => 'Safari浏览器',
            'chrome'     => '谷歌浏览器',
            'namoroka'      => '火狐浏览器',
            'shredder'      => '火狐浏览器',
            'minefield'     => '火狐浏览器',
            'granparadiso'  => '火狐浏览器',
            'firefox'  => '火狐浏览器',
            'micromessenger'=>'微信内浏览器',
            'msie'=>'微信IE内浏览器',
            'webkit'=>'Webkit内核浏览器',
            'opera'=>'欧朋浏览器',
            'konqueror'=>'Konqueror浏览器',
            'gecko'=>'Gecko内核浏览器',
            'googlebot'=>'谷歌爬虫',
            'sogoubrowser'=>'搜狗浏览器',
            'iphone'=>'苹果浏览器',
            'msnbot'=>'微软爬虫',
            'applewebkit'=>'AppleWebkit内核浏览器',
            'ubrowser'=>'UC浏览器',
            'trident'=>'IE浏览器',
            'maxthon'=>'傲游浏览器',
            'lbbrowser'=>'猎豹浏览器'
        );
        if(isset($BrowserNameCn[$this->browserName])){
            return $BrowserNameCn[$this->browserName];
        }else{
            if($this->browserName){
                return $this->browserName;
            }else{
                return '其他浏览器';
            }

        }
    }
    //获取中文版浏览器名字
    public function getOperatingSystemCn()
    {
        $SystemCn=array(
            'android'=>'安卓系统',
            'windows'=>'微软Windows系统',
            'macintosh'=>'MAC系统',
            'linux'=>'Linux系统',
            'freebsd'=>'FreeBSD系统',
            'unix'=>'Uninx系统',
            'iphone'=>'苹果手机系统'
        );
        if(isset($SystemCn[$this->operatingSystem])){
            return $SystemCn[$this->operatingSystem];
        }else{
            return $this->operatingSystem;
        }
    }

  /**
   * Set the browser name
   *
   * @param   string  $name the browser name
   */
  public function setBrowserName($name)
  {
    $this->browserName = $name;
  }

  /**
   * Get the browser version
   *
   * @return string the browser version
   */
  public function getBrowserVersion()
  {
    return $this->browserVersion;
  }

  /**
   * Set the browser version
   *
   * @param   string  $version the browser version
   */
  public function setBrowserVersion($version)
  {
    $this->browserVersion = $version;
  }

  /**
   * Get the operating system name
   *
   * @return  string the operating system name
   */
  public function getOperatingSystem()
  {
    return $this->operatingSystem;
  }

  /**
   * Set the operating system name
   *
   * @param   string $operatingSystem the operating system name
   */
  public function setOperatingSystem($operatingSystem)
  {
    $this->operatingSystem = $operatingSystem;
  }

  /**
   * Get the engine name
   *
   * @return  string the engine name
   */
  public function getEngine()
  {
    return $this->engine;
  }

  /**
   * Set the engine name
   *
   * @param   string $operatingSystem the engine name
   */
  public function setEngine($engine)
  {
    $this->engine = $engine;
  }

  /**
   * Get the user agent string
   *
   * @return  string the user agent string
   */
  public function getUserAgentString()
  {
    return $this->userAgentString;
  }

  /**
   * Set the user agent string
   *
   * @param   string $userAgentString the user agent string
   */
  public function setUserAgentString($userAgentString)
  {
    $this->userAgentString = $userAgentString;
  }

  /**
   * Tell whether this user agent is unknown or not
   *
   * @return boolean  true if this user agent is unknown, false otherwise
   */
  public function isUnknown()
  {
    return empty($this->browserName);
  }

  /**
   * @return string combined browser name and version
   */
  public function getFullName()
  {
    return $this->getBrowserName().' '.$this->getBrowserVersion();
  }

  public function __toString()
  {
    return $this->getFullName();
  }

  /**
   * Configure the user agent from a user agent string
   * @param   string                    $userAgentString        the user agent string
   * @param   \phpUserAgentStringParser  $userAgentStringParser  the parser used to parse the string
   */
  public function configureFromUserAgentString($userAgentString, \phpUserAgentStringParser $userAgentStringParser = null)
  {
    if(null === $userAgentStringParser)
    {
      $userAgentStringParser = new \phpUserAgentStringParser();
    }

    $this->setUserAgentString($userAgentString);

    $this->fromArray($userAgentStringParser->parse($userAgentString));
  }

  /**
   * Convert the user agent to a data array
   *
   * @return  array data
   */
  public function toArray()
  {
    return array(
      'browser_name'      => $this->getBrowserName(),
      'browser_version'   => $this->getBrowserVersion(),
      'operating_system'  => $this->getOperatingSystem()
    );
  }

  /**
   * Configure the user agent from a data array
   *
   * @param array $data
   */
  public function fromArray(array $data)
  {
    $this->setBrowserName($data['browser_name']);
    $this->setBrowserVersion($data['browser_version']);
    $this->setOperatingSystem($data['operating_system']);
    $this->setEngine($data['engine']);
  }
}
