<?php
    /**
    * Sets the current Wow flavor
    * and initializes the Wow engine
    */
    namespace Application;
    use Application\Core\Wow;
    Wow::flavor(Wow::COMBINED);
    Wow::start();