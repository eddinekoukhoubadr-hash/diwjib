#!/bin/bash
# Convertir PORT en integer pour Laravel
export PORT_NUM=$(($PORT))
php artisan serve --host=0.0.0.0 --port=$PORT_NUM