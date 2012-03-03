<?php
//Remember to add some type constrictions!
interface Node {
	public function evaluate(array $varBinds);
	public function __toString();
}

class Variable implements Node {
	private $varName;

	public function  __construct($varName) {
		$this->varName = $varName;
	}

	public function evaluate(array $varBinds) {
		return $varBinds[$this->varName];
	}
	
	public function __toString() {
		return $this->varName;
	}
}

class Func1Arg implements Node {
	private $func;
	private $arg;
	private $funcName;

	public function __construct($func, $arg, $funcName = "1Func") {
		$this->func = $func; 
		$this->arg = $arg;
		$this->funcName = $funcName;
	}

	public function evaluate(array $varBinds) {
		$temp = $this->func;
		return $temp($this->arg->evaluate($varBinds));
	}

	public function __toString() {
		return $this->funcName . "(" . $this->arg . ")";
	}
}

class Func2Arg implements Node {
	private $func;
	private $arg1;
	private $arg2;
	private $funcName;

	public function __construct($func, $arg1, $arg2, $funcName = "2Func") {
		$this->func = $func; 
		$this->arg1 = $arg1;
		$this->arg2 = $arg2;
		$this->funcName = $funcName;
	}

	public function evaluate(array $varBinds) {
		$temp = $this->func;
		return $temp(	$this->arg1->evaluate($varBinds),
						$this->arg2->evaluate($varBinds));
	}

	public function __toString() {
		return $this->funcName . "(" . $this->arg1 . "," . $this->arg2 . ")";
	}
}
