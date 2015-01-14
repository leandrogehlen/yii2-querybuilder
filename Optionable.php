<?php

namespace leandrogehlen\querybuilder;


/**
 * Optionable is the interface that should be implemented by classes who want to support customizable representation
 * of their instances.
 *
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
interface Optionable
{

    /**
     * Converts the model into an options array.
     * @return array the array representation of the object
     */
    public function toOptions();

} 