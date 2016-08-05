<?php
require_once DOL_DOCUMENT_ROOT . '/compta/facture/class/facture.class.php';

class factureArg extends Facture {
	
	function createFromOrden($object)
		{
			global $conf,$user,$langs;
	
			$error=0;
	
			// Closed order
			$this->date = dol_now();
			$this->source = 0;
	
			$num=count($object->lines);
			
			for ($i = 0; $i < $num; $i++)
			{
				$line = new FactureLigne($this->db);
	
				$line->libelle			= $object->lines[$i]->libelle;
				$line->label			= $object->lines[$i]->label;
				$line->desc				= $object->lines[$i]->desc;
				$line->subprice			= $object->lines[$i]->subprice;
				$line->total_ht			= $object->lines[$i]->total_ht;
				$line->total_tva		= $object->lines[$i]->total_tva;
				$line->total_ttc		= $object->lines[$i]->total_ttc;
				$line->tva_tx			= $object->lines[$i]->tva_tx;
				$line->localtax1_tx		= $object->lines[$i]->localtax1_tx;
				$line->localtax2_tx		= $object->lines[$i]->localtax2_tx;
				$line->qty				= $object->lines[$i]->qty;
				$line->fk_remise_except	= $object->lines[$i]->fk_remise_except;
				$line->remise_percent	= $object->lines[$i]->remise_percent;
				$line->fk_product		= $object->lines[$i]->fk_product;
				$line->info_bits		= $object->lines[$i]->info_bits;
				$line->product_type		= $object->lines[$i]->product_type;
				$line->rang				= $object->lines[$i]->rang;
				$line->special_code		= $object->lines[$i]->special_code;
				$line->fk_parent_line	= $object->lines[$i]->fk_parent_line;
	
				$this->lines[$i] = $line;
			}
	
			$this->socid                = $object->socid;
			$this->fk_project           = $object->fk_project;
			$this->cond_reglement_id    = $object->cond_reglement_id;
			$this->mode_reglement_id    = $object->mode_reglement_id;
			$this->availability_id      = $object->availability_id;
			$this->demand_reason_id     = $object->demand_reason_id;
			$this->date_livraison       = $object->date_livraison;
			$this->fk_delivery_address  = $object->fk_delivery_address;
			$this->contact_id           = $object->contactid;
			$this->ref_client           = $object->ref_client;
			$this->note                 = $object->note;
			$this->note_public          = $object->note_public;
	
			$this->origin				= $object->element;
			$this->origin_id			= $object->id;
	
			// Possibility to add external linked objects with hooks
			$this->linked_objects[$this->origin] = $this->origin_id;
			if (! empty($object->other_linked_objects) && is_array($object->other_linked_objects))
			{
				$this->linked_objects = array_merge($this->linked_objects, $object->other_linked_objects);
			}
	
			$ret = $this->create($user);
	
			if ($ret > 0)
			{
				// Actions hooked (by external module)
				include_once DOL_DOCUMENT_ROOT.'/core/class/hookmanager.class.php';
				$hookmanager=new HookManager($this->db);
				$hookmanager->initHooks(array('invoicedao'));
	
				$parameters=array('objFrom'=>$object);
				$action='';
				$reshook=$hookmanager->executeHooks('createFrom',$parameters,$this,$action);    // Note that $action and $object may have been modified by some hooks
				if ($reshook < 0) $error++;
	
				if (! $error)
				{
					return 1;
				}
				else return -1;
			}
			else return -1;
		}

		
		function fetch($rowid, $ref='', $ref_ext='', $ref_int='')
		{
			global $conf;
		
			if (empty($rowid) && empty($ref) && empty($ref_ext) && empty($ref_int)) return -1;
		
			$sql = 'SELECT f.rowid,f.facnumber,f.ref_client,f.ref_ext,f.ref_int,f.type,f.fk_soc,f.amount,f.tva, f.localtax1, f.localtax2, f.total,f.total_ttc,f.remise_percent,f.remise_absolue,f.remise';
			$sql.= ', f.datef as df';
			$sql.= ', f.date_lim_reglement as dlr';
			$sql.= ', f.datec as datec';
			$sql.= ', f.date_valid as datev';
			$sql.= ', f.tms as datem';
			$sql.= ', f.note as note_private, f.note_public, f.fk_statut, f.paye, f.close_code, f.close_note, f.fk_user_author, f.fk_user_valid, f.model_pdf';
			$sql.= ', f.fk_facture_source';
			$sql.= ', f.fk_mode_reglement, f.fk_cond_reglement, f.fk_projet, f.extraparams';
			$sql.= ', p.code as mode_reglement_code, p.libelle as mode_reglement_libelle';
			$sql.= ', c.code as cond_reglement_code, c.libelle as cond_reglement_libelle, c.libelle_facture as cond_reglement_libelle_doc';
			$sql.= ' FROM '.MAIN_DB_PREFIX.'facture as f';
			$sql.= ' LEFT JOIN '.MAIN_DB_PREFIX.'c_payment_term as c ON f.fk_cond_reglement = c.rowid';
			$sql.= ' LEFT JOIN '.MAIN_DB_PREFIX.'c_paiement as p ON f.fk_mode_reglement = p.id';
			$sql.= ' WHERE f.entity = '.$conf->entity;
			if ($rowid)   $sql.= " AND f.rowid=".$rowid;
			if ($ref)     $sql.= " AND f.facnumber='".$this->db->escape($ref)."'";
			if ($ref_ext) $sql.= " AND f.ref_ext='".$this->db->escape($ref_ext)."'";
			if ($ref_int) $sql.= " AND f.ref_int='".$this->db->escape($ref_int)."'";
		
			dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
			$result = $this->db->query($sql);
			if ($result)
			{
				if ($this->db->num_rows($result))
				{
					$obj = $this->db->fetch_object($result);
		
					$this->id					= $obj->rowid;
					$this->ref					= $obj->facnumber;
					$this->ref_client			= $obj->ref_client;
					$this->ref_ext				= $obj->ref_ext;
					$this->ref_int				= $obj->ref_int;
					$this->type					= $obj->type;
					$this->date					= $obj->df;
					$this->date_creation		= $obj->datec;
					$this->date_validation		= $obj->datev;
					$this->datem				= $obj->datem;
					$this->remise_percent		= $obj->remise_percent;
					$this->remise_absolue		= $obj->remise_absolue;
					//$this->remise				= $obj->remise;
					$this->total_ht				= $obj->total;
					$this->total_tva			= $obj->tva;
					$this->total_localtax1		= $obj->localtax1;
					$this->total_localtax2		= $obj->localtax2;
					$this->total_ttc			= $obj->total_ttc;
					$this->paye					= $obj->paye;
					$this->close_code			= $obj->close_code;
					$this->close_note			= $obj->close_note;
					$this->socid				= $obj->fk_soc;
					$this->statut				= $obj->fk_statut;
					$this->date_lim_reglement	= $obj->dlr;
					$this->mode_reglement_id	= $obj->fk_mode_reglement;
					$this->mode_reglement_code	= $obj->mode_reglement_code;
					$this->mode_reglement		= $obj->mode_reglement_libelle;
					$this->cond_reglement_id	= $obj->fk_cond_reglement;
					$this->cond_reglement_code	= $obj->cond_reglement_code;
					$this->cond_reglement		= $obj->cond_reglement_libelle;
					$this->cond_reglement_doc	= $obj->cond_reglement_libelle_doc;
					$this->fk_project			= $obj->fk_projet;
					$this->fk_facture_source	= $obj->fk_facture_source;
					$this->note					= $obj->note_private;	// deprecated
					$this->note_private			= $obj->note_private;
					$this->note_public			= $obj->note_public;
					$this->user_author			= $obj->fk_user_author;
					$this->user_valid			= $obj->fk_user_valid;
					$this->modelpdf				= $obj->model_pdf;
		
					$this->extraparams			= (array) json_decode($obj->extraparams, true);
		
					if ($this->statut == 0)	$this->brouillon = 1;
		
					/*
					 * Lines
					*/
		
					$this->lines  = array();
		
					$result=$this->fetch_lines();
					if ($result < 0)
					{
						$this->error=$this->db->error();
						dol_syslog(get_class($this)."::fetch Error ".$this->error, LOG_ERR);
						return -3;
					}
					return 1;
				}
				else
				{
					$this->error='Bill with id '.$rowid.' or ref '.$ref.' not found sql='.$sql;
					dol_syslog(get_class($this)."::fetch Error ".$this->error, LOG_ERR);
					return 0;
				}
			}
			else
			{
				$this->error=$this->db->error();
				dol_syslog(get_class($this)."::fetch Error ".$this->error, LOG_ERR);
				return -1;
			}
		}
		
	function validar($user, $force_number='', $idwarehouse=0)
		{
			global $conf,$langs;
			
			
			require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
		
			$now=dol_now();
		
			$error=0;
			dol_syslog(get_class($this).'::valate user='.$user->id.', force_number='.$force_number.', idwarehouse='.$idwarehouse);
		
			// Check parameters
			if (! $this->brouillon)
			{
				dol_syslog(get_class($this)."::validate no draft status", LOG_WARNING);
				return 0;
			}
		
			if (! $user->rights->facture->valider)
			{
				$this->error='Permission denied';
				dol_syslog(get_class($this)."::validate ".$this->error, LOG_ERR);
				return -1;
			}
		
			$this->db->begin();
		
			$this->fetch_thirdparty();
			$this->fetch_lines();
		
			// Check parameters
			if ($this->type == 1)		// si facture de remplacement
			{
				// Controle que facture source connue
				if ($this->fk_facture_source <= 0)
				{
					$this->error=$langs->trans("ErrorFieldRequired",$langs->trans("InvoiceReplacement"));
					$this->db->rollback();
					return -10;
				}
		
				// Charge la facture source a remplacer
				$facreplaced=new factureArg($this->$db);
				//$facreplaced=new Facture($this->db);
				$result=$facreplaced->fetch($this->fk_facture_source);
				if ($result <= 0)
				{
					$this->error=$langs->trans("ErrorBadInvoice");
					$this->db->rollback();
					return -11;
				}
		
				// Controle que facture source non deja remplacee par une autre
				$idreplacement=$facreplaced->getIdReplacingInvoice('validated');
				if ($idreplacement && $idreplacement != $this->id)
				{
					$facreplacement=new factureArg($this->db);
					$facreplacement->fetch($idreplacement);
					$this->error=$langs->trans("ErrorInvoiceAlreadyReplaced",$facreplaced->ref,$facreplacement->ref);
					$this->db->rollback();
					return -12;
				}
		
				$result=$facreplaced->set_canceled($user,'replaced','');
				if ($result < 0)
				{
					$this->error=$facreplaced->error;
					$this->db->rollback();
					return -13;
				}
			}
		
			// Define new ref
			if ($force_number)
			{
				$num = $force_number;
			}
			else if (preg_match('/^[\(]?PROV/i', $this->ref))
			{
				if (! empty($conf->global->FAC_FORCE_DATE_VALIDATION))	// If option enabled, we force invoice date
				{
					$this->date=dol_now();
					$this->date_lim_reglement=$this->calculate_date_lim_reglement();
				}
				$num = $this->getNextNumRef($this->client);
			}
			else
			{
				$num = $this->ref;
			}
		
			if ($num)
			{
				$this->update_price(1);
				
				//exit;
				// Validate
				$sql = 'UPDATE '.MAIN_DB_PREFIX.'facture';
				$sql.= " SET facnumber='".$num."', fk_statut = 1, fk_user_valid = ".$user->id.", date_valid = '".$this->db->idate($now)."'";
				if (! empty($conf->global->FAC_FORCE_DATE_VALIDATION))	// If option enabled, we force invoice date
				{
					$sql.= ', datef='.$this->db->idate($this->date);
					$sql.= ', date_lim_reglement='.$this->db->idate($this->date_lim_reglement);
				}
				$sql.= ' WHERE rowid = '.$this->id;

				echo  $sql."<br>";
				exit;
				
				
				$cantItems=count($this->lines);
				$cantMaxLineas=$conf->global->CANT_LINEAS_FC;
				$cantFacturas=ceil($cantItems/$cantMaxLineas)-1;
				
				dol_syslog(get_class($this)."::validate sql=".$sql);
				$resql=$this->db->query($sql);
				
				if (! $resql)
				{
					dol_syslog(get_class($this)."::validate Echec update - 10 - sql=".$sql, LOG_ERR);
					dol_print_error($this->db);
					$error++;
				}
		
				// On verifie si la facture etait une provisoire
				if (! $error && (preg_match('/^[\(]?PROV/i', $this->ref)))
				{
					// La verif qu'une remise n'est pas utilisee 2 fois est faite au moment de l'insertion de ligne
				}
		
				if (! $error)
				{
					// Define third party as a customer
					$result=$this->client->set_as_client();
		
					// Si active on decremente le produit principal et ses composants a la validation de facture
					if ($this->type != 3 && $result >= 0 && ! empty($conf->stock->enabled) && ! empty($conf->global->STOCK_CALCULATE_ON_BILL))
					{
						require_once DOL_DOCUMENT_ROOT.'/product/stock/class/mouvementstock.class.php';
						$langs->load("agenda");
		
						// Loop on each line
						$cpt=count($this->lines);
						for ($i = 0; $i < $cpt; $i++)
						{
						if ($this->lines[$i]->fk_product > 0)
						{
						$mouvP = new MouvementStock($this->db);
						// We decrease stock for product
							if ($this->type == 2) $result=$mouvP->reception($user, $this->lines[$i]->fk_product, $idwarehouse, $this->lines[$i]->qty, $this->lines[$i]->subprice, $langs->trans("InvoiceValidatedInDolibarr",$num));
							else $result=$mouvP->livraison($user, $this->lines[$i]->fk_product, $idwarehouse, $this->lines[$i]->qty, $this->lines[$i]->subprice, $langs->trans("InvoiceValidatedInDolibarr",$num));
							if ($result < 0) { $error++; }
						}
						}
						}
						}
		
						if (! $error)
						{
						$this->oldref = '';
		
                // Rename directory if dir was a temporary ref
		                if (preg_match('/^[\(]?PROV/i', $this->ref))
		                {
		                // On renomme repertoire facture ($this->ref = ancienne ref, $num = nouvelle ref)
		                // afin de ne pas perdre les fichiers attaches
		                $facref = dol_sanitizeFileName($this->ref);
		                $snumfa = dol_sanitizeFileName($num);
		                	$dirsource = $conf->facture->dir_output.'/'.$facref;
		                	$dirdest = $conf->facture->dir_output.'/'.$snumfa;
		                	if (file_exists($dirsource))
		                	{
		                	dol_syslog(get_class($this)."::validate rename dir ".$dirsource." into ".$dirdest);
		
		                	if (@rename($dirsource, $dirdest))
		                	{
		                	$this->oldref = $facref;
		
		                	dol_syslog("Rename ok");
		                	
		                	// Suppression ancien fichier PDF dans nouveau rep
		                	dol_delete_file($conf->facture->dir_output.'/'.$snumfa.'/'.$facref.'*.*');
		                	}
		                	}
		                	}
		                	}
		
		                	// Set new ref and define current statut
		                	if (! $error)
		                	{
		                	$this->ref = $num;
		                	$this->facnumber=$num;
		                	$this->statut=1;
		                	$this->brouillon=0;
		                			$this->date_validation=$now;
		                	}
		
		                	// Trigger calls
		                	if (! $error)
		                	{
		                	// Appel des triggers
		                		include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
		                		$interface=new Interfaces($this->db);
		                		$result=$interface->run_triggers('BILL_VALIDATE',$this,$user,$langs,$conf);
		                		if ($result < 0) { $error++; $this->errors=$interface->errors; }
		                		// Fin appel triggers
            }
		                		}
		                		else
		                		{
		                		$error++;
		                		}
		
		                		if (! $error)
		                		{
		                		$this->db->commit();
		                		return 1;
		                		}
		                		else
		                		{
		                		$this->db->rollback();
		                		$this->error=$this->db->lasterror();
		                			return -1;
		                		}
		                		}
		
	}
	

?>