<?php

/* ISBN 0-201-31663-3 R.Sedgewick: Algorithms in C. program 17.10 */

class ST_node {
var $index;
var $d;
var $l, $m, $r;

function ST_node($d1) {
	$this->index = -1;
	$this->d = $d1;
	$this->l=0; $this->m=0; $this->r=0;
	}
}

class SymbolTable {
var $ST_head;
var $ST_val;
var $ST_N;

function SymbolTable() {
	$this->ST_head = 0;
	$this->ST_N = 0;
	return;
	}

function ST_indexR($ST_h, $ST_v, $ST_w) {
	/* echo("<br> ST_indexR: h=$ST_h, v[0]=$ST_v[0], v[1]=$ST_v[1], w=$ST_w"); */
	$i = $ST_v[$ST_w];
	/* echo("<br> ST_indexR: i=$i"); */
	if ($ST_h == 0) $ST_h = new ST_node($i);
	/* echo("<br> h->index: $ST_h->index, d: $ST_h->d, l: $ST_h->l, m: $ST_h->m, r: $ST_h->r "); */
	if ($i == 0) {
		if ($ST_h->index == -1) $ST_h->index = $this->ST_N++;
		$this->ST_val = $ST_h->index;
		return $ST_h;
		}
	if ($i <  $ST_h->d) $ST_h->l = $this->ST_indexR($ST_h->l, $ST_v, $ST_w);
	if ($i == $ST_h->d) $ST_h->m = $this->ST_indexR($ST_h->m, $ST_v, $ST_w+1);
	if ($i >  $ST_h->d) $ST_h->r = $this->ST_indexR($ST_h->r, $ST_v, $ST_w);
	return $ST_h;
	}

function ST_index($ST_key) {
	/* echo("<br> ST_index: key=$ST_key, head=$this->ST_head, val=$this->ST_val"); */
	$this->ST_head = $this->ST_indexR($this->ST_head, $ST_key, 0);
	return $this->ST_val;
	}
}

?>