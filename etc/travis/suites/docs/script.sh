#!/usr/bin/env bash

source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/../../../bash/common.lib.sh"

tfold "Building: Documentation" "docker run -it --rm sylius-docs"
