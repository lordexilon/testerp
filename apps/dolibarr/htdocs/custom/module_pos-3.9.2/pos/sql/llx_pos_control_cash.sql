-- ===================================================================
-- Copyright (C) 2011 Juanjo Menent <jmenent@2byte.es>
--
-- This program is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 2 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program; if not, write to the Free Software
-- Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
--
-- $Id: llx_pos_control_cash.sql,v 1.4 2011-08-22 10:34:40 jmenent Exp $
-- ===================================================================

create table llx_pos_control_cash
(
  rowid           	integer AUTO_INCREMENT PRIMARY KEY,
  ref				varchar(30) NOT NULL,
  entity            integer DEFAULT 1 NOT NULL,
  fk_cash	     	integer,
  fk_user	      	integer,
  date_c			datetime,
  type_control		tinyint  DEFAULT 0, --(0=Cierre, 1=Control, 2=Turno)
  amount_teor		double(24,8),
  amount_real		double(24,8),
  amount_diff		double(24,8),
  amount_mov_out	double(24,8),
  amount_mov_int	double(24,8),
  amount_next_day	double(24,8),
  comment			text
)ENGINE=innodb;
