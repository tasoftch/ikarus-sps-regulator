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
use Ikarus\SPS\Regulator\Part\PartInterface;

interface FeatureInterface
{
    /**
     * Called on first regulation request
     *
     * @param FeaturedRegulatorInterface $regulator
     * @return void
     */
    public function regulatorWillLaunch(FeaturedRegulatorInterface $regulator);

    /**
     * Called to reset the regulation parts
     *
     * @param FeaturedRegulatorInterface $regulator
     * @return void
     */
    public function regulatorReset(FeaturedRegulatorInterface $regulator);

    /**
     * Called before regulation process will start
     *
     * @param int|float $requiredValue
     * @param int|float $existingValue
     * @return void
     */
    public function regulatorWillProcess(FeaturedRegulatorInterface $regulator, &$requiredValue, &$existingValue);

    /**
     * @param FeaturedRegulatorInterface $regulator
     * @param PartInterface $part
     * @param int|float $value
     * @return int|float
     */
    public function regulatorPartProcess(FeaturedRegulatorInterface $regulator, PartInterface $part, $value);

    /**
     * @param FeaturedRegulatorInterface $regulator
     * @param int|float $requiredValue
     * @param int|float $existingValue
     * @return void
     */
    public function regulatorDidProcess(FeaturedRegulatorInterface $regulator, $requiredValue, $existingValue);
}