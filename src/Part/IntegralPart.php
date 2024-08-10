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

namespace Ikarus\SPS\Regulator\Part;

class IntegralPart implements PartInterface, PartResetInterface, TimedPartInterface
{
    protected $sum;

    private $reset_on_sign_change;

    private $samplingTime = 0.0;

    /**
     * @param int|float $sum
     * @param bool $reset_on_sign_change
     */
    public function __construct($sum = 0, bool $reset_on_sign_change = false)
    {
        $this->sum = $sum;
        $this->reset_on_sign_change = $reset_on_sign_change;
    }


    /**
     * @inheritDoc
     */
    public function regulateValue($value)
    {
        if($this->doesResetOnSignChange()) {
            $negative = function($v) { return $v < 0; };

            if($negative($this->sum) != $negative($value))
                $this->reset();
        }
        $this->addValue($value);
        return $this->sum;
    }

    protected function addValue($value) {
        if($this->samplingTime > 0)
            $this->sum += $value * $this->samplingTime;
        else
            $this->sum += $value;
    }

    public function reset()
    {
        $this->sum = 0;
    }

    /**
     * @return bool
     */
    public function doesResetOnSignChange(): bool
    {
        return $this->reset_on_sign_change;
    }

    /**
     * @return float|int
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * @param float|int $sum
     * @return IntegralPart
     */
    public function setSum($sum = 0)
    {
        $this->sum = $sum;
        return $this;
    }

    public function setSamplingTime(float $time)
    {
        $this->samplingTime = $time;
    }
}