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
		$this->weight[] = $weight;
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
		$atIndex = -1; //So we get an error message if we fuck up.
		
		if($rand < $this->weight[0]->get($this->turns)) {
			$atIndex = 0;
		}
		else {
			$accum = $this->weight[0]->get($this->turns);
			for($i = 1; $i < count($this->weight); ++$i) {
				if($rand < $accum + $this->weight[$i]->get($this->turns) && $rand >= $accum) {
					$atIndex = $i;
				}
				$accum += $this->weight[$i]->get($this->turns);
			}
		}
		
		$builder = $this->builders[$atIndex];
		return $builder($this);
	}
}
