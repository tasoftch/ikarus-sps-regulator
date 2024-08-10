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

use Ikarus\SPS\Regulator\Feature\FeatureInterface;

class FeaturedRegulator extends AbstractRegulator implements FeaturedRegulatorInterface
{
    private $initial = true;

    private $features = [];

    public function addFeature(FeatureInterface $feature) {
        $this->features[] = $feature;
        return $this;
    }

    public function regulate($requiredValue, $existingValue)
    {
        if($this->initial) {
            $this->initial = false;
            array_walk($this->features, function(FeatureInterface $feature) {
                $feature->regulatorWillLaunch($this);
            });
        }

        array_walk($this->features, function(FeatureInterface $feature) use (&$requiredValue, &$existingValue) {
            $feature->regulatorWillProcess($this, $requiredValue, $existingValue);
        });

        $v = $requiredValue - $existingValue;
        foreach ($this->getParts() as $part) {
            array_walk($this->features, function(FeatureInterface $feature) use (&$part, &$v) {
                $v = $feature->regulatorPartProcess($this, $part, $v);
            });
        }

        array_walk($this->features, function(FeatureInterface $feature) use (&$requiredValue, &$existingValue) {
            $feature->regulatorDidProcess($this, $requiredValue, $existingValue);
        });
        return $v;
    }

    public function reset()
    {
        array_walk($this->features, function(FeatureInterface $feature) {
            $feature->regulatorReset($this);
        });
    }
}