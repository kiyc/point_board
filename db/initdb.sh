#!/bin/sh

cat create_tables.sql | sqlite3 point_board.db
