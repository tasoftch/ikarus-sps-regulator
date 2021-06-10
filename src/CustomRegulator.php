<?php
/*
 * BSD 3-Clause License
 *
 * Copyright (c) 2019, TASoft Applications
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *  Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 *  Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 *  Neither the name of the copyright holder nor the names of its
 *   contributors may be used to endorse or promote products derived from
 *   this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

namespace Ikarus\SPS\Regulator;


use Ikarus\SPS\Regulator\Element\RegulatorElementInterface;

class CustomRegulator extends AbstractRegulator
{


	/** @var RegulatorElementInterface[] */
	private $elements = [];



	/**
	 * @param RegulatorElementInterface $element
	 * @return $this
	 */
	public function addElement(RegulatorElementInterface $element) {
		$this->elements[] = $element;
		return $this;
	}

	/**
	 * @param RegulatorElementInterface $element
	 * @return $this
	 */
	public function removeElement(RegulatorElementInterface $element) {
		if(($idx = array_search($element, $this->elements)) !== false)
			unset($this->elements[$idx]);
		return $this;
	}

	/**
	 * @return $this
	 */
	public function clearElements() {
		$this->elements = [];
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function regulate($requiredValue, $existingValue)
	{
		$e = $requiredValue - $existingValue;

		$v = 0;
		foreach ($this->elements as $element)
			$v += $element->regulateValue($e, $this->cache);

		$this->appendDeviation($e);
		return $v;
	}
}