#!/bin/sh
LDFLAGS="-L/home/villalbamedina/dolibarr-3.9.1-1/common/lib $LDFLAGS"
export LDFLAGS
CFLAGS="-I/home/villalbamedina/dolibarr-3.9.1-1/common/include $CFLAGS"
export CFLAGS
CXXFLAGS="-I/home/villalbamedina/dolibarr-3.9.1-1/common/include $CXXFLAGS"
export CXXFLAGS
		    
PKG_CONFIG_PATH="/home/villalbamedina/dolibarr-3.9.1-1/common/lib/pkgconfig"
export PKG_CONFIG_PATH
