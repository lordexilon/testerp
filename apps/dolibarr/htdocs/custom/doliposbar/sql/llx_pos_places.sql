-- ===================================================================
-- Copyright (C) 2012      Ferran Marcet        <fmarcet@2byte.es>
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
-- $Id: llx_pos_ticket.sql,v 1.1 2011-08-04 16:33:26 jmenent Exp $
-- ===================================================================


create table llx_pos_places
(
  rowid               	integer AUTO_INCREMENT PRIMARY KEY,

  entity				integer  DEFAULT 1 	NOT NULL,
  name		        	varchar(30)        	NOT NULL,
  description  			text,
  fk_ticket            	integer  DEFAULT NULL,
  status			  	integer  DEFAULT 1 	NOT NULL,
  fk_user_c		    	integer,
  fk_user_m		    	integer,
  datec					datetime,
  datea					datetime,
  left_pos				float,
  top_pos				float,
  zone					int(3)
  
)ENGINE=innodb;
