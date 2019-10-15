<?php

/**
 * Product:       Xtento_StockImport (2.2.3)
 * ID:            hP1u+MkFuWCNii6DNKWj0Z9DGtXJm+UAxW8tNX6mHhE=
 * Packaged:      2015-01-11T18:18:35+00:00
 * Last Modified: 2013-08-07T16:53:10+02:00
 * File:          app/code/local/Xtento/StockImport/Model/Source/Interface.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

interface Xtento_StockImport_Model_Source_Interface
{
    public function testConnection();
    public function loadFiles();
    public function archiveFiles($filesToProcess, $forceDelete = false);
}