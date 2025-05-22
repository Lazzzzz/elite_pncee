<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    protected $table = 'rapports';
    protected $primaryKey = 'rapport_id';
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $connection = 'elite';


    protected $fillable = [
        'rapport_parent',
        'user_id',
        'validation',
        'type_rapport',
        'nom_rapport',
        'rdv_id',
        'version',
        'client_id',
        'delegataire_id',
        'date_rapport',
        'operation_id',
        'proprietaire_name',
        'proprietaire_adresse',
        'proprietaire_cp',
        'proprietaire_ville',
        'nature_travaux',
        'statut_rdv',
        'vue_client',
        'conformite',
        'beneficiaire_id',
        'proprietaire_id',
        'type_operation',
        'type_inspection',
        'created',
        'modified',
    ];
}
