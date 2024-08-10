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

namespace Ikarus\SPS\Regulator\Feature;

use Ikarus\SPS\Regulator\FeaturedRegulatorInterface;
use Ikarus\SPS\Regulator\Limits;
use Ikarus\SPS\Regulator\Part\RangeAwarePartInterface;

class RangeFeature extends AbstractFeature
{
    /** @var int|float */
    private $minimum;
    /** @var int|float */
    private $maximum;

    /** @var int|float */
    private $range;

    /**
     * @param int|float|Limits $minimum
     * @param int|float $maximum
     */
    public function __construct($minimum, $maximum = 1)
    {
        if($minimum instanceof Limits) {
            list($this->minimum, $this->maximum) = $minimum;
            return;
        }

        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }

    /**
     * @return float|int
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * @return float|int
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * @return float|int
     */
    public function getRange()
    {
        return $this->range;
    }

    public function regulatorWillProcess(FeaturedRegulatorInterface $regulator, &$requiredValue, &$existingValue)
    {
        $range = ( $this->getRange() - $this->getMinimum() ) / ( $this->getMaximum() - $this->getMinimum() );

        foreach($regulator->getParts() as $part) {
            if($part instanceof RangeAwarePartInterface) {
                $part->setRange($range);
            }
        }
    }

    /**
     * @param float|int $range
     * @return RangeFeature
     */
    public function setRange($range)
    {
        $this->range = $range;
        return $this;
    }
}