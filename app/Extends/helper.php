<?php
/**
 * Created by PhpStorm.
 * User: zhaoyadong
 * Date: 2017/2/4
 * Time: 下午5:33
 */

if (!function_exists("encode")) {
	/**
	 * @param $data
	 * @param int $expire
	 * @return mixed
	 */
	function encode($data, $expire = 0)
	{
		$key = md5(env("APP_KEY"));
		$data = base64_encode($data);
		$x = 0;
		$len = strlen($data);
		$l = strlen($key);
		$char = '';
		for ($i = 0; $i < $len; $i++) {
			if ($x == $l) $x = 0;
			$char .= substr($key, $x, 1);
			$x++;
		}
		$str = sprintf('%010d', $expire ? $expire + time() : 0);
		for ($i = 0; $i < $len; $i++) {
			$str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
		}
		return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
	}
}

if (!function_exists("decode")) {
	/**
	 * @param $data
	 * @return bool|string
	 */
	function decode($data)
	{
		$key = md5(env("APP_KEY"));
		$data = str_replace(array('-', '_'), array('+', '/'), $data);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		$data = base64_decode($data);
		$expire = substr($data, 0, 10);
		$data = substr($data, 10);
		if ($expire > 0 && $expire < time()) {
			return '';
		}
		$x = 0;
		$len = strlen($data);
		$l = strlen($key);
		$char = $str = '';
		for ($i = 0; $i < $len; $i++) {
			if ($x == $l) $x = 0;
			$char .= substr($key, $x, 1);
			$x++;
		}
		for ($i = 0; $i < $len; $i++) {
			if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
				$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
			} else {
				$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
			}
		}
		return base64_decode($str);

	}
}


if (!function_exists("RC4Encrypt")) {
	/**
	 * @param $data  array 明文
	 * @return string
	 */
	function RC4Encrypt(array $data)
	{
		if (is_array($data)) {
			$data = json_encode($data);
		}
		$key = env('APP_KEY');
		$pwd = md5(md5($key) . $key);
		//因为RC4是二进制加密算法，所以密文是无法直接当作文本查看
		return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(RC4($pwd, $data)));
	}
}
if (!function_exists("RC4Decrypt")) {
	/**
	 * 解密
	 * @param $cipher string 密文
	 * @return array
	 */
	function RC4Decrypt($cipher)
	{
		$key = env('APP_KEY');
		$pwd = md5(md5($key) . $key);
		return json_decode(RC4($pwd, base64_decode(str_replace(['-', '_'], ['+', '/'], $cipher))), true);
	}
}

if (!function_exists("RC4")) {
	/**
	 * 加密算法
	 * @param $pwd
	 * @param $data
	 * @return string
	 */
	function RC4($pwd, $data)
	{
		$key[] = "";
		$box[] = "";
		$cipher = '';

		$pwd_length = strlen($pwd);
		$data_length = strlen($data);

		for ($i = 0; $i < 256; $i++) {
			$key[$i] = ord($pwd[$i % $pwd_length]);
			$box[$i] = $i;
		}

		for ($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $key[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for ($a = $j = $i = 0; $i < $data_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;

			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;

			$k = $box[(($box[$a] + $box[$j]) % 256)];
			$cipher .= chr(ord($data[$i]) ^ $k);
		}

		return $cipher;
	}
}
if (!function_exists('success')) {
	/**
	 * @param mixed $data
	 * @param string $url
	 * @param string $msg
	 * @param int $code
	 * @return array
	 */
	function success($data = [], $msg = '操作成功', $code = SUCCESS, $url = '')
	{
		return response(['msg' => $msg, 'code' => $code, 'url' => $url, 'data' => $data]);
	}
}


if (!function_exists('fail')) {
	/**
	 * @param array $data
	 * @param string $msg
	 * @param int $code
	 * @return array
	 */
	function fail($msg = '操作失败', $code = FAIL, $data = [])
	{
		$data or $data = [];
		return response(['msg' => $msg, 'code' => $code, 'data' => $data]);
	}
}

if (!function_exists('uuid')) {
	/**
	 * @return string
	 */
	function uuid()
	{
		// fix for compatibility with 32bit architecture; seed range restricted to 62bit
		$seed = mt_rand(0, 2147483647) . '#' . mt_rand(0, 2147483647);

		// Hash the seed and convert to a byte array
		$val = md5($seed, true);
		$byte = array_values(unpack('C16', $val));

		// extract fields from byte array
		$tLo = ($byte[0] << 24) | ($byte[1] << 16) | ($byte[2] << 8) | $byte[3];
		$tMi = ($byte[4] << 8) | $byte[5];
		$tHi = ($byte[6] << 8) | $byte[7];
		$csLo = $byte[9];
		$csHi = $byte[8] & 0x3f | (1 << 7);

		// correct byte order for big edian architecture
		if (pack('L', 0x6162797A) == pack('N', 0x6162797A)) {
			$tLo = (($tLo & 0x000000ff) << 24) | (($tLo & 0x0000ff00) << 8)
				| (($tLo & 0x00ff0000) >> 8) | (($tLo & 0xff000000) >> 24);
			$tMi = (($tMi & 0x00ff) << 8) | (($tMi & 0xff00) >> 8);
			$tHi = (($tHi & 0x00ff) << 8) | (($tHi & 0xff00) >> 8);
		}

		// apply version number
		$tHi &= 0x0fff;
		$tHi |= (3 << 12);

		// cast to string
		$uuid = sprintf(
			'%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
			$tLo,
			$tMi,
			$tHi,
			$csHi,
			$csLo,
			$byte[10],
			$byte[11],
			$byte[12],
			$byte[13],
			$byte[14],
			$byte[15]
		);

		return $uuid;
	}
}

if (!function_exists('backWithError')) {
	/**
	 * @param $errorMsg
	 * @return $this
	 */
	function backWithError($errorMsg)
	{
		return redirect()->back()->withErrors($errorMsg)->withInput();
	}
}
if (!function_exists('upload_url')) {

	/**
	 * 获取上传目录URL
	 *
	 * @param string|null $path
	 * @param string $type
	 * @param bool $secure
	 * @return string
	 */
	function upload_url($path = null, $type = '', $secure = null)
	{
		$configName = 'path.upload';
		$type && $configName .= '_' . $type;

		$relatePath = str_replace(public_path(), '', config($configName));
		return str_replace('\\', '/', asset($relatePath . $path, $secure));
	}
}
if (!function_exists('getRealIp')) {
	/**
	 * @return mixed
	 */
	function getRealIp()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip is pass from proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}

if (!function_exists('getMenuTree')) {
	/**
	 * @param array $menu
	 * @param array $selected
	 * @param string $field
	 */
	function getMenuTree($menu = [], $selected = [], $field = 'id')
	{
		foreach ($menu as $key => $value) {
			echo '<li id="' . $value [$field] . '" ' . (!empty ($selected)
				&& in_array($value ['id'], $selected) ? 'class="selected"' : "") . '>' . $value ['name'];
			if (isset ($value ['child']) && is_array($value ['child'])) {
				echo '<ul>';
				getMenuTree($value ['child'], $selected, $field);
				echo '</ul></li>';
			} else {
				echo '</li>';
			}
		}
	}
}

if (!function_exists('toTree')) {
	/**
	 * @param $data
	 * @param string $pField
	 * @param string $child
	 * @return array
	 */
	function toTree($data, $pField = 'pid', $child = 'child')
	{
		$tree = [];
		foreach ($data as $item) {
			if (isset($data[$item[$pField]])) {
				$data[$item[$pField]][$child][] = &$data[$item['id']];
			} else {
				$tree[] = &$data[$item['id']];
			}
		}
		return $tree;
	}
}

if (!function_exists('humansTime')) {
	/**
	 * @param int $time
	 * @return string
	 */
	function humansTime($time = 0)
	{
		$parse = $time ? Carbon\Carbon::parse($time) : Carbon\Carbon::now();
		$hour = $parse->hour;
		if ($hour < 3) {
			$str = '深夜';
		} else if ($hour < 5) {
			$str = '凌晨';
		} else if ($hour < 8) {
			$str = '清晨';
		} else if ($hour < 12) {
			$str = '上午';
		} else if ($hour < 14) {
			$str = '中午';
		} else if ($hour < 18) {
			$str = '下午';
		} else if ($hour < 19) {
			$str = '傍晚';
		} else if ($hour < 23) {
			$str = '晚上';
		} else {
			$str = '深夜';
		}
		return $str;
	}
}

if (!function_exists('getOrderNo')) {
	/**
	 * 随机生成订单号
	 * @return string
	 */
	function getOrderNo($prefix = 'XM')
	{
		list($_, $mic) = explode(' ', microtime());
		return $prefix . date('Ymd') . substr($mic, -4) . rand(10, 99);
	}
}

if (!function_exists('getAction')) {
	/**
	 * 获取访问的模块
	 * @return string
	 */
	function getAction()
	{
		$pattern = trim($_SERVER['REQUEST_URI'], '/');
		return $pattern == '' ? 'index' : explode('/', $pattern)[1];
	}
}

if (!function_exists('getCurrentAction')) {
	/**
	 * 当前控制器信息
	 * @return array
	 */
	function getCurrentAction()
	{
		$action = \Illuminate\Support\Facades\Route::current()->getActionName();
		list($class, $method) = explode('@', $action);
		$action = substr($class, strrpos($class, '\\') + 1);
		return ['action' => $action, 'method' => $method];
	}
}

if (!function_exists('ip2address')) {
	/**
	 * @param $ip
	 * @return string
	 */
	function ip2address($ip)
	{
		$info = file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
		$json = explode('=', $info);
		$info = json_decode(substr($json[1], 0, -1), true);
		return $info['province'] . $info['city'];
	}
}

if (!function_exists('getUploadField')) {
	/**
	 * @param string $fieldName
	 * @param string $msg
	 * @param string $dataType
	 * @param string $buttonClass
	 * @param string $fieldValue
	 */
	function getUploadField($fieldName = 'img', $msg = '图片', $dataType = 'img', $buttonClass = 'info', $fieldValue = '')
	{
		$multiFileName = $fieldName;
		if (in_array($dataType, ['multi-img', 'multi-file'])) {
			$multiFileName .= '[]';
		}
		$addStr = '';
		$input = $dataType == 'img' || $dataType == 'file' ? "<input type=hidden id=$fieldName name=$multiFileName value=$fieldValue>" : '';
		if ($fieldValue) {
			if ($dataType == 'file') {
				$addStr = '<p style="font-size: 18px">' . pathinfo($fieldValue)['basename'] . '</p>';
			} elseif ($dataType == 'multi-file') {
				$fieldName .= '[]';
				foreach ($fieldValue as $v) {
					if (isset($v['img'])) {
						$path = $v['img'];
					} elseif (isset($v['path'])) {
						$path = $v['path'];
					}
					$addStr .= "<div><p style=\"font-size: 18px\">{pathinfo($path)['basename']}<input type=\"hidden\" name=\"{$fieldName}[]\" value=\"$v\" /></p><a  class=\"btn btn-danger file-delete\">删除</a></div>";
				}
			} elseif ($dataType == 'multi-img') {
				$fieldName .= '[]';
				foreach ($fieldValue as $v) {
					if (isset($v['img'])) {
						$path = $v['img'];
					} elseif (isset($v['path'])) {
						$path = $v['path'];
					}
					$addStr .= "<div><img src=\"$path\" /><i class=\"fa fa-trash image-delete\" style=\"position: relative;top: 0;right: 0;\">删除</i><input type=\"hidden\" name=\"{$fieldName}[]\" value=\"$path\" /></div>";
				}
			} else {
				$addStr = '<img src="' . $fieldValue . '" />';
			}
		}
		echo <<<EOF
		<div class="form-group">
			<label class="control-label">
			{$msg} <span class="symbol required"></span>
			</label>
			{$input}
			<a class="btn btn-{$buttonClass} file-upload" data-type={$dataType} data-name={$multiFileName}>上传{$msg}</a>
			<div class="show-img">
			{$addStr}
			</div>
		</div>
EOF;
	}
}

if (!function_exists('getTextField')) {
	/**
	 * @param string $fieldName
	 * @param string $msg
	 * @param string $filedType
	 * @param string $fieldValue
	 */
	function getTextField($fieldName = 'name', $msg = '姓名', $filedType = 'text', $fieldValue = '')
	{
		echo <<<EOF
		<div class="form-group">
                 <label class="control-label">
                  {$msg} <span class="symbol required"></span>
                 </label>
                 <input type="{$filedType}" placeholder="请填写{$msg}" class="form-control" id="{$fieldName}" name="{$fieldName}" value="{$fieldValue}">
        </div>
EOF;
	}
}


if (!function_exists('getTextareaField')) {
	/**
	 * @param string $fieldName
	 * @param string $msg
	 * @param boolean $isRich
	 * @param string $fieldValue
	 */
	function getTextareaField($fieldName = 'content', $msg = '内容', $isRich = false, $fieldValue = '')
	{
		$id = $isRich ? 'text-container' : $fieldName;
		$class = $isRich ? '' : 'class=form-control';
		echo <<<EOF
		<div class="form-group">
	        <label class="control-label">
	            {$msg} <span class="symbol required"></span>
	        </label>
	        <textarea name="{$fieldName}" id="{$id}" {$class} placeholder="请填写{$msg}">{$fieldValue}</textarea>
	    </div>
EOF;
	}
}


if (!function_exists('getRadioField')) {
	/**
	 * @param string $fieldName
	 * @param string $msg
	 * @param array $fieldCollect
	 * @param string $fieldValue
	 */
	function getRadioField($fieldName = 'name', $msg = '姓名', $fieldCollect = [], $fieldValue = '')
	{
		$collect = '';
		foreach ($fieldCollect as $k => $v) {
			$collect .= '<input type="radio"  id="' . $fieldName . '" name="' . $fieldName . '" value="' . $k . '"';
			$collect .= ($k == $fieldValue || !$k ? "checked" : "") . ' />' . $v;
		}

		echo <<<EOF
		<div class="form-group">
	        <label class="control-label">
	            {$msg} <span class="symbol required"></span>
	        </label>
	        <div class="form-controls">
	            {$collect}
	        </div>
        </div>
EOF;

	}
}

if (!function_exists('formatDate')) {
	/**
	 * @param $dateTimeString
	 * @return string
	 */
	function formatDate($dateTimeString)
	{
		return Carbon\Carbon::parse($dateTimeString)->toDateString();
	}
}

if (!function_exists('formatDateTime')) {
	/**
	 * @param $dateTimeString
	 * @return string
	 */
	function formatDateTime($dateTimeString)
	{
		return Carbon\Carbon::parse($dateTimeString)->toDateTimeString();
	}
}

if (!function_exists('sendCurl')) {

	/**
	 * @param $url
	 * @param array $body
	 * @param array $header
	 * @param string $type
	 * @return mixed
	 * @throws Exception
	 */
	function sendCurl($url, $body = [], $header = [], $type = "GET")
	{
		//1.创建一个curl资源
		$ch = curl_init();
		//2.设置URL和相应的选项
		curl_setopt($ch, CURLOPT_URL, $url);//设置url
		//1)设置请求头
//		array_push($header, 'Accept:application/json');
//		array_push($header,'Content-Type:application/json');
		//array_push($header, 'http:multipart/form-data');
		//设置为false,只会获得响应的正文(true的话会连响应头一并获取到)
		curl_setopt($ch, CURLOPT_HEADER, 0);
		// 设置超时限制防止死循环
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		//设置发起连接前的等待时间，如果设置为0，则无限等待。
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//2)设备请求体
		if (count($body) > 0) {
			//$b=json_encode($body,true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));//全部数据使用HTTP协议中的"POST"操作来发送。
		}
		//设置请求头
		if (count($header) > 0) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		//上传文件相关设置
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// 对认证证书来源的检查
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);// 从证书中检查SSL加密算
		//3)设置提交方式
		switch (strtoupper($type)) {
			case "GET":
				curl_setopt($ch, CURLOPT_HTTPGET, true);
				break;
			case "POST":
				curl_setopt($ch, CURLOPT_POST, true);
				break;
			case "PUT"://使用一个自定义的请求信息来代替"GET"或"HEAD"作为HTTP请求。这对于执行"DELETE" 或者其他更隐蔽的HTT
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				break;
			case "DELETE":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
		}
		//4)在HTTP请求中包含一个"User-Agent: "头的字符串。-----必设

		curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');

		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)'); // 模拟用户使用的浏览器
		//5)


		//3.抓取URL并把它传递给浏览器
		$res = curl_exec($ch);
		$result = json_decode($res, true);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$err = curl_error($ch);
		//4.关闭curl资源，并且释放系统资源
		curl_close($ch);
		if ($httpCode != 200) {
			$msg = $err ? $err : '请求第三方服务器失败';
			if($result && $result['message']){
				$msg = $result['message'];
			}
			\Illuminate\Support\Facades\Log::info($msg);
			throw new \Exception($msg);
		}
		return $result ? $result : $res;
	}
}
