#!/bin/bash
cp ../../../public/index.php public/index.php
cp -r ../../../lib/ lib/

tar -cvf project.tar bases/ controller/  lib/  public/  views/ conf/  layout/
md5sum project.tar 

