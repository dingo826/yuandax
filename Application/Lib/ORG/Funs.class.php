<?php
class Funs {
	function yinle($name){
        $name = implode('', $name);
        $url  = 'http://httop1.duapp.com/mp3.php?musicName=' . $name;
        $str  = file_get_contents($url);
        $obj  = json_decode($str);
		$arr['Title']        = $name;
		$arr['Description']  = $name;
		$arr['MusicUrl']     = $obj->url;
		$arr['HQMusicUrl']   = $obj->url;

		$content['MsgType'] = "Music";
		
		$content['Music'][] = $arr;
		$content['FuncFlag'] = "0";
		return $content;
    }

	function kuaidi($data) {
        $data = array_merge($data);
		if(is_numeric($data[1])) {
			$name = $data[0];
			$num  = $data[1];
		}else {
			$name = $data[1];
			$num  = $data[0];
		}
        $str  = file_get_contents('http://www.weinxinma.com/api/index.php?m=Express&a=index&name=' . $name . '&number=' . $num);
		$content['MsgType'] = "text";
		$content["Content"] = $str;
		return $content;
    }

	function rili($n) {		
		$year  = date("Y");
		$month = date("m");
		$day   = date("d");
		$url   = 'http://www.sheup.com/rilibiao.php?year='.$year.'&month='.$month.'&day='.$day;
		$rili  = self::httpGetRequest($url);
		$rili  = iconv('gbk', 'utf-8', $rili);
		$prel  = "/<div class=\"huangdao_text\">(.*)<div class=\"huangdao_text\">/is";
		preg_match_all($prel, $rili, $match);
		$str   = $match[1][0];

		$prel  = "/<h3>(.*)<\/h3>/is";
		preg_match_all($prel, $str, $match);
		$h3    = $match[1][0];
		$ren   = $h3."\n";

		$prel  = "/<ul [^>]*>(.*)<\/ul>/isU";
		preg_match_all($prel, $str, $match);
		$arr   = $match[1];
		foreach($arr as $key => $val) {
			$prel  = "/<li class=\"Partition\">(.*)<\/li>.*<li class=\"quest\">(.*)<\/li>/isU";
			preg_match_all($prel, $val, $match);
			$tit   = strip_tags($match[1][0]);

			$ren  .= $tit.": ".$match[2][0]."\n";
		}
		$content['MsgType'] = "text";
		$content["Content"] = $ren;
		return $content;
	}

	function tianqi($n) {
        $name = implode('', $n);
        @$str = 'http://api.ajaxsns.com/api.php?key=free&appid=0&msg=' . urlencode('天气' . $name);
		
        $json = json_decode(file_get_contents($str),true);
        $str  = str_replace('{br}', "\n", $json['content']);
        $content['MsgType'] = "text";
		$content["Content"] = str_replace('菲菲', 'AI9', $str);
		return $content;
    }

	function fanyi($name) {
		import("@.ORG.Http");
        $name = array_merge($name);
        $url  = "http://openapi.baidu.com/public/2.0/bmt/translate?client_id=kylV2rmog90fKNbMTuVsL934&q=" . $name[0] . "&from=auto&to=auto";
        $json = Http::fsockopenDownload($url);
        if ($json == false) {
            $json = file_get_contents($url);
        }
        $json = json_decode($json);
        $str  = $json->trans_result;
        if ($str[0]->dst == false)
            return "主淫，".$name[0]."还没学过";
		$dst  = str_replace(' ', "", $str[0]->dst);
		if($json->to=='zh') $mp3url = C('HTTP_DOMIN').'zxdu.php?key='.urlencode($dst).'$`'.$json->to;
		else $mp3url = C('HTTP_DOMIN').'zxdu.php?key='.urlencode($dst).'$`'.$json->to;
		$arr['Title']        = $str[0]->src;
		$arr['Description']  = $str[0]->dst;
		$arr['MusicUrl']     = $mp3url;
		$arr['HQMusicUrl']   = $mp3url;

		$content['MsgType'] = "Music";
		
		$content['Music'][] = $arr;
		$content['FuncFlag'] = "0";
		return $content;
    }

	function baike($name) {
        $name = implode('', $name);
        $name_gbk         = iconv('utf-8', 'gbk', $name);
        $encode           = urlencode($name_gbk);
        $url              = 'http://baike.baidu.com/list-php/dispose/searchword.php?word=' . $encode . '&pic=1';
        $get_contents     = self::httpGetRequest_baike($url);
        $get_contents_gbk = iconv('gbk', 'utf-8', $get_contents);
        preg_match("/URL=(\S+)'>/s", $get_contents_gbk, $out);
        $real_link     = 'http://baike.baidu.com' . $out[1];
        $get_contents2 = self::httpGetRequest_baike($real_link);
        preg_match('#"Description"\scontent="(.+?)"\s\/\>#is', $get_contents2, $matchresult);
        if (isset($matchresult[1]) && $matchresult[1] != "") {
            $text = htmlspecialchars_decode($matchresult[1]);
        } else {
            $text = "抱歉，没有找到与“" . $name . "”相关的百科结果。";
        }
		$content['MsgType'] = "text";	
		$content['Content'] = $text;
		return $content;
    }

    function httpGetRequest_baike($url) {
        $headers = array(
            "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Accept-Language: en-us,en;q=0.5",
            "Referer: http://www.baidu.com/"
        );
        $ch      = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($ch);
        curl_close($ch);
        if ($output === FALSE) {
            return "cURL Error: " . curl_error($ch);
        }
        return $output;
    }

	function httpGetRequest($url) {
        $headers = array(
            "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Accept-Language: en-us,en;q=0.5"
        );
        $ch      = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($ch);
        curl_close($ch);
        if ($output === FALSE) {
            return "cURL Error: " . curl_error($ch);
        }
		
        return $output;
    }
}
?>