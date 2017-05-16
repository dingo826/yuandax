<?php

class TagLibLists extends TagLib {
	protected $tags = array(
		'listt' => array('attr'=>'limit,order', 'close'=>1)
		);

	public function _list($attr, $content) {
		$attr = $this->parseXmlAttr($attr);
		$limit = $attr['limit'];
		$order = $attr['order'];
		$str = '<?php ';
		$str .= '$field=array("id", "title", "hits");';
		$str .= '$_list_news=M("ces")->field($field)->limit('.$limit.')->order("'.$order.'")->select();';
		$str .= 'foreach($_list_news as $_list_value):';
		$str .= 'extract($_list_value);';
		$str .= $content;
		$str .= '<?php endforeach ?>';
		return $str;
	}

}