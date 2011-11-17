#!/bin/bash

#############################################################################
# PHPCV
#
# LICENSE
#
# This source file is subject to the new BSD license that is bundled
# with this package in the file LICENSE.txt.
# If you did not receive a copy of the license and are unable to
# obtain it through the world-wide-web, please send an email
# to mail@dasprids.de so I can send you a copy immediately.
#
# @category   PHPCV
# @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
# @license    New BSD License
#############################################################################

if [ $# -ne 1 ]
then
  echo "Usage: `basename $0` path/to/classmap_generator.php"
  exit 65
fi

if [ ! -f $1 ]
then
  echo "Classmap generator not found"
  exit 65
fi

DIR="$( cd -P "$( dirname "$0" )" && pwd )"

php "$1" -l "$DIR/../src" -o "$DIR/../src/autoload_classmap.php" -w

CLASSMAP="$(cat $DIR/../src/autoload_classmap.php | grep -v '<?php')"
HEADER='<?php
/**
 * PHPCV
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mail@dasprids.de so I can send you a copy immediately.
 *
 * @category   PHPCV
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

'

echo "${HEADER}${CLASSMAP}" > "$DIR/../src/autoload_classmap.php"
