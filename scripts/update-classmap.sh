#!/bin/bash

#############################################################################
# Flitch
#
# @link      http://github.com/DASPRiD/Flitch For the canonical source repository
# @copyright 2011-2012 Ben Scholzen 'DASPRiD'
# @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
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
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

'

echo "${HEADER}${CLASSMAP}" > "$DIR/../src/autoload_classmap.php"
