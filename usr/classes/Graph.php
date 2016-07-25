<?php

/* ISBN 0-201-31663-3 R.Sedgewick: Algorithms in C. program 17.6 */

class Link
{
var $v;
var $next;

function Link($Index, $NextLink) {
	$this->v=$Index;
	$this->next=$NextLink;
	return;
	}
}

class Graph
{
var $V;
var $E;
var $adj;

function Graph($Vertex) {

	$this->V=$Vertex;
	$this->E=0;
	for ($i=0; $i<$Vertex; $i++)
		$this->adj[$i]=0;
	return;
	}

function insertE($v, $w) {
	$this->adj[$v] = new Link($w, $this->adj[$v]);
	$this->adj[$w] = new Link($v, $this->adj[$w]);
	$this->E++;
	return;
	}
}

?>