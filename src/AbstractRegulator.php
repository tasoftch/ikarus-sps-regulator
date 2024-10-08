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

use Ikarus\SPS\Regulator\Part\PartInterface;

abstract class AbstractRegulator implements RegulatorInterface
{
    private $parts = [];

    public function __construct(...$parts)
    {
        $add = function($parts) use (&$add) {
            foreach($parts as $part) {
                if($part instanceof PartInterface)
                    $this->parts[] = $part;
                elseif(is_iterable($part))
                    $add($part);
            }
        };
        $add($parts);
    }

    /**
     * @param PartInterface $part
     * @return $this
     */
    public function addPart(PartInterface $part): AbstractRegulator
    {
        $this->parts[] = $part;
        return $this;
    }

    /**
     * @param PartInterface $part
     * @return $this
     */
    public function removePart(PartInterface $part): AbstractRegulator {
        if(($idx = array_search($part, $this->parts, true)) !== false) {
            unset($this->parts[$idx]);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function clearParts(): AbstractRegulator {
        $this->parts = [];
        return $this;
    }

    /**
     * @return PartInterface[]
     */
    public function getParts(): array
    {
        return $this->parts;
    }
}