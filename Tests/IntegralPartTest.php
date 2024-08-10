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

use Ikarus\SPS\Regulator\Part\IntegralPart;
use PHPUnit\Framework\TestCase;

class IntegralPartTest extends TestCase
{
    public function testPlainIntegralPart() {
        $ip = new IntegralPart();

        $this->assertEquals(3, $ip->regulateValue(3));
        $this->assertEquals(5.5, $ip->regulateValue(2.5));
        $this->assertEquals(6.5, $ip->regulateValue(1));
        $this->assertEquals(4.5, $ip->regulateValue(-2));
        $this->assertEquals(3, $ip->regulateValue(-1.5));

        $ip->reset();

        $this->assertEquals(-6, $ip->regulateValue(-6));
    }

    public function testIntegralPartWithOffset() {
        $ip = new IntegralPart(3);

        $this->assertEquals(4, $ip->regulateValue(1));
    }

    public function testIntegralPartWithSignSwitch() {
        $ip = new IntegralPart(0, true);

        $this->assertEquals(3, $ip->regulateValue(3));
        $this->assertEquals(6, $ip->regulateValue(3));
        $this->assertEquals(-3, $ip->regulateValue(-3));
        $this->assertEquals(-6, $ip->regulateValue(-3));
    }
}
