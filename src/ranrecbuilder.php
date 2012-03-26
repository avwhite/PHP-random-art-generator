<?php
class DifVal {
	private $val;
	private $diff;

	public function __construct($val, $diff) {
		$this->val = $val;
		$this->diff = $diff;
	}

	public function get($time) {
		return $this->val + $this->diff * $time;
	}

	public function plus(DifVal $other) {
		return new DifVal($this->val + $other->val, $this->diff + $other->diff);
	}
}

class RandRecBuilder {
	private $builders;
	private $weight;
	private $totalWeight;
	private $turns;

	public function __construct() {
		$this->builders = array();
		$this->weight = array();
		$this->totalWeight = new DifVal(0,0);
		$this->turns = -1;
	}

	public function addBuilder($builder, DifVal $weight) {
		$this->builders[] = $builder;
		if(count($this->weight) == 0) {
			$this->weight[] = $weight;
		} else {
			$this->weight[] = end($this->weight)->plus($weight);
		}
		$this->totalWeight = $this->totalWeight->plus($weight);
	}

	public function build($amount) {
		$res = array();
		++$this->turns;
		for($i = 0; $i < $amount; ++$i) {
			$res[] = $this->auxBuild();
		}
		--$this->turns;
		return $res;
	}

	public function buildOne() {
		++$this->turns;
		$res = $this->auxBuild();
		--$this->turns;
		return $res;
	}

	private function auxBuild() {
		$rand = rand(0,$this->totalWeight->get($this->turns)-1);
		$atIndex = $this->binarySearch($rand, 0, count($this->weight) - 1); 
		$builder = $this->builders[$atIndex];
		return $builder($this);
	}

	//modified: search for smallest value larger than key
	//not as efficient as normal binary search, always runs in
	//worst case time. T(logN + 1)
	private function binarySearch($key, $low, $high) {
		if($high == $low) {
			return $low;
		}
		if($high < $low) {
			return -1;
		}

		$mid = floor(($low + $high)/2);
		$val = $this->weight[$mid]->get($this->turns) ;
	
		if($key < $val) {
			return $this->binarySearch($key, $low, $mid);
		} elseif($key >= $val) {
			return $this->binarySearch($key, $mid+1, $high);
		}
		
	}
}
