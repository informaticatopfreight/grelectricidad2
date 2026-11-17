#!/usr/bin/env bash
# Genera dist/gr-electricidad-claro.zip listo para subir en Apariencia → Temas → Añadir nuevo tema.
set -euo pipefail
cd "$(dirname "$0")"
mkdir -p dist
rm -f dist/gr-electricidad-claro.zip
zip -rq -X dist/gr-electricidad-claro.zip gr-electricidad-claro -x '*.DS_Store' -x '*/.git*'
echo "Creado dist/gr-electricidad-claro.zip ($(du -h dist/gr-electricidad-claro.zip | cut -f1))"
