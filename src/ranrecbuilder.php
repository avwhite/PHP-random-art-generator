<?php
class RandRecBuilder {
	private $builders;
	private $cumWeight;
	private $totalCumulative;
	private $turns;

	public function __construct() {
		$this->builders = array();
		$this->cumWeight = array();
		$this->totalCumulative = 0;
		$this->turns = 0;
	}

	public function addBuilder($builder, $weight) {
		$this->builders[] = $builder;
		$this->cumWeight[] = end($this->cumWeight) + $weight;
		$this->totalCumulative += $weight;
	}

	public function build($amount) {
		$res = array();
		for($i = 0; $i < $amount; ++$i) {
			$res[] = $this->auxBuild();
		}
		++$this->turns;
		return $res;
	}

	private function auxBuild() {
		$rand = rand(0,$this->totalCumulative-1);
		$atIndex = -1;
		
		if($rand < $this->cumWeight[0]) {
			$atIndex = 0;
		}
		else {
			for($i = 1; $i < count($this->cumWeight); ++$i) {
				if($rand < $this->cumWeight[$i] && $rand >= $this->cumWeight[$i - 1]) {
					$atIndex = $i;
				}
			}
		}
		
		$builder = $this->builders[$atIndex];
		return $builder($this);
	}
}
