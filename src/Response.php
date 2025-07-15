<?php

namespace HexagonDev\HiraKataLearner;

class Response
{
    public function __construct(
        public mixed $content,
        public int $statusCode = 200,
    ) {
        //
    }
}