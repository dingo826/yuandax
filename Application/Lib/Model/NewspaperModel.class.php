<?php
/**
 * 报刊库model
 * 2017/1/11
 */
class NewspaperModel extends Model
{
	private	$_model;
	private	$_modelcontent;

	public function __construct()
	{
		parent::__construct();
		$this->_model = M('newspapers_lists');
		$this->_modelcontent = M('newspapers_contents');
	}

	

	/**
	 * 获取资讯详情
	 *
	 * @param	int/str		记录id
	 * @param	boole		返回数据格式 true:id关联数据格式，默认数字索引
	 * @return	array
	 */
	public function getInfoDetailed($id, $assoc = false) {
		$detail = $this->_model->field("setid, thumb as picurl, title")->where("id='".$id."'")->find();

		$newspapers_category_set = M("jd_newspapers_category_set")->where("id='".$detail['setid']."'")->find();
		$statement = "本文转载自".$newspapers_category_set['newspapername'];

		$content = $this->_modelcontent->where("listid='".$id."'")->find();
		$show_cover_pic = 1;
		if($content['opicdata']) {
			$show_cover_pic = -1;
			if(!$content['tranpicdata']) {
				$num = preg_match_all("/<img.*src=[\"|\'](http\:\/\/.+)[\"|\']/isU", $content['opicdata'], $match);
				$ycimgarr = array_unique($match[1]);
				if($ycimgarr) {
					$imgcon = '<div style="text-align: center;">';
					foreach($ycimgarr as $key => $val) {
						if(!strstr($val, 'http://www.yuandax.com')) {
							//echo $val;exit;
							$xzhimg = GrabImage($val);
							$xzhimg = substr($xzhimg, 1);
							//$imgcon .= '<img src="'.$xzhimg.'" style="width: 100%!important;"><br/>';
							$imgcon .= '<img src="'.$xzhimg.'"><br/>';
						}
					}
					$imgcon .= "</div><br/>";
					$updata["tranpicdata"] = $imgcon;
					$this->_modelcontent->where("listid='".$id."'")->save($updata);
				}
			}else {
				$imgcon = $content['tranpicdata'];
			}
		}

		/*$desc = htmlspecialchars_decode($content["content"]);
		$desc = preg_replace("/<style>.+<\/style>/is", "", $desc);
		$desc = preg_replace("/<script>.+<\/script>/is", "", $desc);
		$desc = mb_substr(trimall(strip_tags($desc)), 0, 80);

		preg_match('/[^，|,|。|.|?]+/u', $desc, $matches);
		$insertflag = $matches[0];*/
		$content['content'] = "<div style='color: red;'>（".$statement."）</div><br/>".$content['content'];
		//$content['content'] = str_replace($insertflag, "<a style='color: #ccc'>（".$statement."）</a>".$insertflag, $content['content']);

		
		if(!$content["desc"]) {
			$detail["desc"] = $desc;
			//$desc = htmlspecialchars_decode($content["content"]);
			//$desc = preg_replace("/<style>.+<\/style>/is", "", $desc);
			//$desc = preg_replace("/<script>.+<\/script>/is", "", $desc);
			//echo htmlspecialchars_decode($content["content"]);exit;
			//echo trimall(strip_tags($desc));exit;
			//$detail["desc"]    = mb_substr(trimall(strip_tags($content["content"])), 0, 80);
			//$detail["desc"]    = mb_substr(trimall(strip_tags($desc)), 0, 80);

			//preg_match('/[^，|,|。|.|?]+/u', $detail["desc"], $matches);
		    //$insertflag = $matches[0];

			//$content['content'] = str_replace($insertflag, "(".$statement.")".$insertflag, $content['content']);			
		}

		$detail["content"] = $imgcon.$content["content"];
		$detail["show_cover_pic"]    = $show_cover_pic;

		return $detail;
	}

	function updatesort($list) {
		$titlerules   = M("jd_newspapers_sortrules_types")->where("id=1")->find();
		$contentrules = M("jd_newspapers_sortrules_types")->where("id=2")->find();
		$picrules     = M("jd_newspapers_sortrules_types")->where("id=3")->find();

		$keywordrules = M("jd_newspapers_sortrules_types")->where("id=4")->find();

		foreach($list as $key => $val) {
			$list[$key]["titlelen"]            = mb_strlen(trimall($list[$key]["title"]));

			$quhtmlcontent = htmlspecialchars_decode($list[$key]["content"]);
			$quhtmlcontent = preg_replace("/<style>.+<\/style>/is", "", $quhtmlcontent);
			$quhtmlcontent = preg_replace("/<script>.+<\/script>/is", "", $quhtmlcontent);
			$quhtmlcontent = str_replace(';nbsp;','',$quhtmlcontent);
			$quhtmlcontent = str_replace('&amp','',$quhtmlcontent);
			$quhtmlcontent = str_replace('&lt;br&gt;','',$quhtmlcontent);
			$quhtmlcontent = strip_tags($quhtmlcontent);
			$quhtmlcontent = trimall(preg_replace('/[\r|\n]/','',$quhtmlcontent));

			$list[$key]["contentlen"]            = mb_strlen($quhtmlcontent);

			$val["titlelen"] = $list[$key]["titlelen"];
			$val["contentlen"] = $list[$key]["contentlen"];


			$list[$key]["titlescore"]          = $this->titleScore($val["titlelen"]);
			$list[$key]["contentscore"]        = $this->contentScore($val["contentlen"]);
			$list[$key]["picscore"]            = $this->picScore($val["picnums"], $val["isusedefaultpic"]);

			$titlekeywordnums     = $this->titlekeywordCostnum($val["title"], $val["jdid"]);
			$contentkeywordnums   = $this->contentkeywordCostnum($val["content"], $val["jdid"]);
			$keywordnums          = $titlekeywordnums+$contentkeywordnums;

			$list[$key]["keywordScore"]        = $this->keywordScore($keywordnums);

			$list[$key]["sortsocre"]    = $list[$key]["titlescore"]*$titlerules["per"]+$list[$key]["contentscore"]*$contentrules["per"]+$list[$key]["picscore"]*$picrules["per"]+$list[$key]["keywordScore"]*$keywordrules["per"];
			//print_r($list[$key]);exit;
			//echo date("Y-m-d", $list[$key]["ctime"]);
			//echo strtotime(date("Y-m-d", $list[$key]["ctime"]));exit;

			$sortsocre[$key]            = $list[$key]["sortsocre"]+strtotime(date("Y-m-d", $list[$key]["ctime"]));

			$list[$key]["titlerulesper"]           = $titlerules["per"];
			$list[$key]["contentrulesper"]         = $contentrules["per"];
			$list[$key]["picrulesper"]             = $picrules["per"];
			$list[$key]["keywordrulesper"]         = $keywordrules["per"];
		}
		//print_r($sortsocre);exit;

		array_multisort($sortsocre, SORT_DESC, $list);
		return $list;
		
	}

	function titleScore($titlelen) {
		$rules = M("jd_newspapers_sortrules")->where("categoryid=1 and min<=".$titlelen)->order("min desc")->find();
		$score = Intval($rules["val"]);
		return $score;
	}

	function contentScore($contentlen) {
		$rules = M("jd_newspapers_sortrules")->where("categoryid=2 and min<=".$contentlen)->order("min desc")->find();
		$score = Intval($rules["val"]);
		if($contentlen>10000) $score = $score+1;
		else $score = $score+($contentlen/10000);
		return $score;
	}

	function picScore($picnums, $isusedefaultpic) {
		$nums = $picnums;
		if($isusedefaultpic==-1) $nums++;
		$rules = M("jd_newspapers_sortrules")->where("categoryid=3 and min<=".$nums)->order("min desc")->find();
		$score = Intval($rules["val"]);
		return $score;
	}

	function keywordScore($keywordnums, $isusedefaultpic) {
		$nums = $picnums;
		if($isusedefaultpic==-1) $nums++;
		$rules = M("jd_newspapers_sortrules")->where("categoryid=4 and min<=".$keywordnums)->order("min desc")->find();
		$score = Intval($rules["val"]);
		return $score;
	}

	function titlekeywordCostnum($title, $jdid) {
		$rules = M("jd_newspapers_keywordrules")->where("jdid='".$jdid."' and `range`=1 and isenable='1'")->select();
		$scorelist = '';
		foreach($rules as $key => $val) {
			$scorelist[$val['val']][] = $val["keyword"];
		}

		$num = 0;
		foreach($scorelist as $key => $val) {
			$blacklist="/".implode("|",$val)."/i";
			$checknum = preg_match_all($blacklist, $title, $matches);
			$num += $checknum;

		}
		return $num;
	}

	function contentkeywordCostnum($content, $jdid) {
		$rules = M("jd_newspapers_keywordrules")->where("jdid='".$jdid."' and `range`=2 and isenable='1'")->select();
		$scorelist = '';
		foreach($rules as $key => $val) {
			$scorelist[$val['val']][] = $val["keyword"];
		}

		$quhtmlcontent = htmlspecialchars_decode($content);
	    $quhtmlcontent = preg_replace("/<style>.+<\/style>/is", "", $quhtmlcontent);
	    $quhtmlcontent = preg_replace("/<script>.+<\/script>/is", "", $quhtmlcontent);
		$quhtmlcontent = str_replace(';nbsp;','',$quhtmlcontent);
		$quhtmlcontent = str_replace('&amp','',$quhtmlcontent);
		$quhtmlcontent = str_replace('&lt;br&gt;','',$quhtmlcontent);
		$quhtmlcontent = trimall(strip_tags(preg_replace('/[\r|\n]/','',$quhtmlcontent)));

		$num = 0;
		foreach($scorelist as $key => $val) {
			$blacklist="/".implode("|",$val)."/i";
			$checknum = preg_match_all($blacklist, $quhtmlcontent, $matches);
			$num += $checknum;

		}
		return $num;
	}
}
