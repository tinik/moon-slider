<?php

use Magento\Framework\Component\ComponentRegistrar;

$registrar = new ComponentRegistrar();

if ($registrar->getPath(ComponentRegistrar::MODULE, 'Tinik_MoonSlider') === null) {
    ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Tinik_MoonSlider', __DIR__);
}
