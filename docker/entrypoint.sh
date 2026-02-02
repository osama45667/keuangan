#!/bin/sh
set -e

# Ensure only one MPM is enabled (prefork) before Apache starts.
a2dismod -f mpm_event mpm_worker mpm_prefork >/dev/null 2>&1 || true
rm -f /etc/apache2/mods-enabled/mpm_*
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf
a2enmod mpm_prefork >/dev/null 2>&1 || true
a2enmod rewrite >/dev/null 2>&1 || true

exec apache2-foreground
