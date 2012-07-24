// model de tipo usuario
Ext.define('RESCATE.model.Profesional', {
    extend: 'Ext.data.Model',
    idProperty: 'profesional_id',
    fields: [
    {
        name: 'profesional_id',
        defaultValue: ''
    }, {
        name: 'usuario_nombre',
        defaultValue: ''
    }, {
        name: 'usuario_login',
        defaultValue: ''
    }, {
        name: 'usuario_apellido1',
        defaultValue: ''
    }, {
        name: 'usuario_apellido2',
        defaultValue: ''
    }, {
        name: 'profesional_tipo',
        defaultValue: 'Restaurador'
    }, {
        name: 'profesional_nombre',
        defaultValue: ''
    }, {
        name: 'profesional_apellido1',
        defaultValue: ''
    }, {
        name: 'profesional_apellido2',
        defaultValue: ''
    }, {
        name: 'profesional_sexo',
        defaultValue: t('Man')
    }, {
        name: 'profesional_observaciones',
        defaultValue: ''
    }, {
        name: 'profesional_paisn',
        defaultValue: 'España'
    }, {
        name: 'profesional_provincian',
        defaultValue: ''
    }, {
        name: 'profesional_poblacionn',
        defaultValue: ''
    }, {
        name: 'profesional_direccionn',
        defaultValue: ''
    }, {
        name: 'profesional_cordenadasn',
        defaultValue: ''
    }, {
        name: 'profesional_fechan',
        defaultValue: ''
    }, {
        name: 'profesional_paisd',
        defaultValue: 'España'
    }, {
        name: 'profesional_provinciad',
        defaultValue: ''
    }, {
        name: 'profesional_poblaciond',
        defaultValue: ''
    }, {
        name: 'profesional_direcciond',
        defaultValue: ''
    }, {
        name: 'profesional_cordenadasd',
        defaultValue: ''
    }, {
        name: 'profesional_fechad',
        defaultValue: ''
    }, {
        name: 'profesional_familia',
        defaultValue: ''
    }, {
        name: 'profesional_archivo',
        defaultValue: ''
    }, {
        name: 'profesional_enlace',
        defaultValue: ''
    }, {
        name: 'profesional_miniatura',
        defaultValue: CONFIG.UrlRelArchivos + '/persona.png'
    }, {
        name: 'profesional_supervisado',
        defaultValue: ''
    }, {
        name: 'profesional_usuario_id',
        defaultValue: ''
    }, {
        name: 'profesional_ultimamod',
        defaultValue: ''
    }, {
        name: 'profesional_bloqueado',
        defaultValue: ''
    }]
});
// model de tipo usuario
Ext.define('RESCATE.model.Obra', {
    extend: 'Ext.data.Model',
    idProperty: 'obra_id',
    fields: [
    {
        name: 'obra_id',
        defaultValue: ''
    }, {
        name: 'obra_tecnica_id',
        defaultValue: ''
    }, {
        name: 'obra_dimension_altura',
        defaultValue: ''
    }, {
        name: 'obra_dimension_anchura',
        defaultValue: ''
    }, {
        name: 'obra_dimension_profundidad',
        defaultValue: ''
    }, {
        name: 'obra_dimension_m2',
        defaultValue: ''
    }, {
        name: 'obra_pais',
        defaultValue: 'España'
    }, {
        name: 'obra_provincia',
        defaultValue: ''
    }, {
        name: 'obra_poblacion',
        defaultValue: ''
    }, {
        name: 'obra_direccion',
        defaultValue: ''
    }, {
        name: 'obra_cordenadas',
        defaultValue: ''
    }, {
        name: 'obra_nombre',
        defaultValue: ''
    }, {
        name: 'obra_fecha1',
        defaultValue: ''
    }, {
        name: 'obra_siglo',
        defaultValue: ''
    }, {
        name: 'obra_acdc',
        defaultValue: ''
    }, {
        name: 'obra_detalles',
        defaultValue: ''
    }, {
        name: 'obra_supervisado',
        defaultValue: ''
    }, {
        name: 'usuario_nombre',
        defaultValue: ''
    }, {
        name: 'usuario_login',
        defaultValue: ''
    }, {
        name: 'usuario_apellido1',
        defaultValue: ''
    }, {
        name: 'usuario_apellido2',
        defaultValue: ''
    }, {
        name: 'obra_usuario_id',
        defaultValue: ''
    }, {
        name: 'obra_bloqueado',
        defaultValue: ''
    }, {
        name: 'obra_ultimamod',
        defaultValue: ''
    }, {
        name: 'profesional_nombre',
        defaultValue: ''
    }, {
        name: 'profesional_apellido1',
        defaultValue: ''
    }, {
        name: 'profesional_apellido2',
        defaultValue: ''
    }, {
        name: 'ubicacion_nombre',
        defaultValue: ''
    }, {
        name: 'ubicacion_pais',
        defaultValue: ''
    }, {
        name: 'ubicacion_provincia',
        defaultValue: ''
    }, {
        name: 'ubicacion_poblacion',
        defaultValue: ''
    }]
});
// model de tipo usuario
Ext.define('RESCATE.model.Usuario', {
    extend: 'Ext.data.Model',
    idProperty: 'usuario_id',
    fields: [
    {
        name: 'usuario_id',
        defaultValue: ''
    }, {
        name: 'usuario_nombre',
        defaultValue: ''
    }, {
        name: 'usuario_login',
        defaultValue: ''
    }, {
        name: 'usuario_password',
        defaultValue: ''
    }, {
        name: 'usuario_rol_id',
        defaultValue: ''
    }, {
        name: 'usuario_email',
        defaultValue: ''
    }, {
        name: 'usuario_apellido1',
        defaultValue: ''
    }, {
        name: 'usuario_apellido2',
        defaultValue: ''
    }, {
        name: 'rol_nombre',
        defaultValue: ''
    }, {
        name: 'usuario_detalles',
        defaultValue: ''
    }]
});
// model de rol
Ext.define('RESCATE.model.Rol', {
    extend: 'Ext.data.Model',
    idProperty: 'rol_id',
    fields: [
    {
        name: 'rol_id',
        defaultValue: ''
    }, {
        name: 'rol_nombre',
        defaultValue: ''
    }, {
        name: 'rol_descripcion',
        defaultValue: ''
    }, {
        name: 'rol_basico',
        defaultValue: ''
    }, {
        name: 'rol_permisos',
        defaultValue: ''
    }],
    associations: [
    {
        type: 'hasMany',
        model: 'RESCATE.model.Permiso',
        name: 'rol_permisos'
    }]
});
// model de permiso
Ext.define('RESCATE.model.Permiso', {
    extend: 'Ext.data.Model',
    idProperty: 'permiso_id',
    fields: [
    {
        name: 'permiso_id',
        defaultValue: ''
    }, {
        name: 'permiso_nombreInterno',
        defaultValue: ''
    }, {
        name: 'permiso_nombre',
        defaultValue: ''
    }, {
        name: 'permiso_descripcion',
        defaultValue: ''
    }, {
        name: 'permiso_activo',
        defaultValue: ''
    }],
    associations: [
    {
        type: 'belongsTo',
        model: 'RESCATE.model.Rol'
    }]
});
// model de filtro de búsqueda
Ext.define('RESCATE.model.Filtro', {
    extend: 'Ext.data.Model',
    fields: [
    {
        name: 'nombre',
        defaultValue: ''
    }, {
        name: 'valor',
        defaultValue: ''
    }]
});
// model de Reconocimiento
Ext.define('RESCATE.model.Reconocimiento', {
    extend: 'Ext.data.Model',
    idProperty: 'reconocimiento_id',
    fields: [
    {
        name: 'reconocimiento_id',
        defaultValue: ''
    }, {
        name: 'reconocimiento_profesional_id',
        defaultValue: ''
    }, {
        name: 'reconocimiento_nombre',
        defaultValue: ''
    }, {
        name: 'reconocimiento_detalles',
        defaultValue: ''
    }, {
        name: 'reconocimiento_fecha',
        defaultValue: ''
    }]
});
// model de Noticia
Ext.define('RESCATE.model.Noticia', {
    extend: 'Ext.data.Model',
    idProperty: 'noticia_id',
    fields: [
    {
        name: 'noticia_id',
        defaultValue: ''
    }, {
        name: 'noticia_titulo',
        defaultValue: ''
    }, {
        name: 'noticia_cuerpo',
        defaultValue: ''
    }, {
        name: 'noticia_publicada',
        defaultValue: 1
    }, {
        name: 'noticia_portada',
        defaultValue: ''
    }, {
        name: 'noticia_fecha',
        defaultValue: ''
    }, {
        name: 'usuario_nombre',
        defaultValue: ''
    }, {
        name: 'noticia_usuario_id',
        defaultValue: ''
    }, {
        name: 'usuario_apellido1',
        defaultValue: ''
    }, {
        name: 'usuario_apellido2',
        defaultValue: ''
    }, {
        name: 'usuario_login',
        defaultValue: ''
    }, {
        name: 'noticia_ultimamod',
        defaultValue: ''
    }]
});
// model de Formacion
Ext.define('RESCATE.model.Formacion', {
    extend: 'Ext.data.Model',
    idProperty: 'formacion_id',
    fields: [
    {
        name: 'formacion_id',
        defaultValue: ''
    }, {
        name: 'formacion_profesional_id',
        defaultValue: ''
    }, {
        name: 'formacion_centro_id',
        defaultValue: ''
    }, {
        name: 'formacion_titulo',
        defaultValue: ''
    }, {
        name: 'formacion_detalles',
        defaultValue: ''
    }, {
        name: 'formacion_fechainicio',
        defaultValue: ''
    }, {
        name: 'formacion_fechafin',
        defaultValue: ''
    }, {
        name: 'formacion_actualmente',
        defaultValue: ''
    }, {
        name: 'centro_nombre',
        defaultValue: ''
    }, {
        name: 'centro_codigo',
        defaultValue: ''
    }, {
        name: 'centro_detalles',
        defaultValue: ''
    }]
});
// model de Cargo
Ext.define('RESCATE.model.Cargo', {
    extend: 'Ext.data.Model',
    idProperty: 'cargo_id',
    fields: [
    {
        name: 'cargo_id',
        defaultValue: ''
    }, {
        name: 'cargo_profesional_id',
        defaultValue: ''
    }, {
        name: 'cargo_centro_id',
        defaultValue: ''
    }, {
        name: 'cargo_nombre',
        defaultValue: ''
    }, {
        name: 'cargo_principal',
        defaultValue: ''
    }, {
        name: 'cargo_actualmente',
        defaultValue: ''
    }, {
        name: 'cargo_departamento',
        defaultValue: ''
    }, {
        name: 'cargo_detalles',
        defaultValue: ''
    }, {
        name: 'cargo_fechainicio',
        defaultValue: ''
    }, {
        name: 'cargo_fechafin',
        defaultValue: ''
    }, {
        name: 'centro_nombre',
        defaultValue: ''
    }, {
        name: 'centro_codigo',
        defaultValue: ''
    }, {
        name: 'centro_detalles',
        defaultValue: ''
    }]
});
// model de Asociacion
Ext.define('RESCATE.model.Asociacion', {
    extend: 'Ext.data.Model',
    idProperty: 'asociacion_id',
    fields: [
    {
        name: 'asociacion_id',
        defaultValue: ''
    }, {
        name: 'asociacionprofesional_profesional_id',
        defaultValue: ''
    }, {
        name: 'asociacion_nombre',
        defaultValue: ''
    }, {
        name: 'asociacion_detalles',
        defaultValue: ''
    }, {
        name: 'asociacion_fecha',
        defaultValue: ''
    }, {
        name: 'asociacionprofesional_fechasalida',
        defaultValue: ''
    }, {
        name: 'asociacionprofesional_fechaentrada',
        defaultValue: ''
    }, {
        name: 'asociacionprofesional_detalles',
        defaultValue: ''
    }]
});

// model de Ubicacion
Ext.define('RESCATE.model.Ubicacion', {
    extend: 'Ext.data.Model',
    idProperty: 'ubicacion_id',
    fields: [
    {
        name: 'ubicacion_id',
        defaultValue: ''
    }, {
        name: 'ubicacionobra_obra_id',
        defaultValue: ''
    }, {
        name: 'ubicacion_nombre',
        defaultValue: ''
    }, {
        name: 'ubicacion_pais',
        defaultValue: ''
    }, {
        name: 'ubicacion_provincia',
        defaultValue: ''
    }, {
        name: 'ubicacion_poblacion',
        defaultValue: ''
    }, {
        name: 'ubicacion_direccion',
        defaultValue: ''
    }, {
        name: 'ubicacion_cordenadas',
        defaultValue: ''
    }, {
        name: 'ubicacionobra_detalles',
        defaultValue: ''
    }]
});
// model genérico de idNombre
Ext.define('RESCATE.model.idNombre', {
    extend: 'Ext.data.Model',
    fields: [
    {
        name: 'id',
        defaultValue: ''
    }, {
        name: 'nombre',
        defaultValue: ''
    }]
});
// model de tipo bibliografiaprofesional
Ext.define('RESCATE.model.ProfesionalBibliografia', {
    extend: 'Ext.data.Model',
    idProperty: 'profesionalbibliografia_id',
    fields: [
    {
        name: 'profesionalbibliografia_id',
        defaultValue: ''
    }, {
        name: 'profesionalbibliografia_bibliografia_id',
        defaultValue: ''
    }, {
        name: 'profesionalbibliografia_profesional_id',
        defaultValue: ''
    }, {
        name: 'profesionalbibliografia_detalles',
        defaultValue: ''
    }, {
        name: 'profesionalbibliografia_tiporelacion',
        defaultValue: ''
    }, {
        name: 'profesional_id',
        defaultValue: ''
    }, {
        name: 'profesional_nombre',
        defaultValue: ''
    }, {
        name: 'profesional_apellido1',
        defaultValue: ''
    }, {
        name: 'profesional_apellido2',
        defaultValue: ''
    }]
});


// model de tipo Profesional-Obra
Ext.define('RESCATE.model.ProfesionalObra', {
    extend: 'Ext.data.Model',
    idProperty: 'id',
	idgen: 'sequential',
    fields: [
    {
        name: 'id',
        defaultValue: ''
    }, {
        name: 'profesionalobra_obra_id',
        defaultValue: ''
    }, {
        name: 'profesionalobra_profesional_id',
        defaultValue: ''
    }, {
        name: 'profesionalobra_detalles',
        defaultValue: ''
    }, {
        name: 'profesional_nombre',
        defaultValue: ''
    }, {
        name: 'profesional_apellido1',
        defaultValue: ''
    }, {
        name: 'profesional_apellido2',
        defaultValue: ''
    }]
});

// model de tipo Profesional-Documento
Ext.define('RESCATE.model.ProfesionalDocumento', {
    extend: 'Ext.data.Model',
    idProperty: 'id',
	idgen: 'sequential',
    fields: [
    {
        name: 'id',
        defaultValue: ''
    }, {
        name: 'profesionaldocumento_documento_id',
        defaultValue: ''
    }, {
        name: 'profesionaldocumento_profesional_id',
        defaultValue: ''
    }, {
        name: 'profesionaldocumento_detalles',
        defaultValue: ''
    }, {
        name: 'profesional_nombre',
        defaultValue: ''
    }, {
        name: 'profesional_apellido1',
        defaultValue: ''
    }, {
        name: 'profesional_apellido2',
        defaultValue: ''
    }]
});

// model de bibliografia
Ext.define('RESCATE.model.Bibliografia', {
    extend: 'Ext.data.Model',
    idProperty: 'bibliografia_id',
    fields: [
    {
        name: 'bibliografia_id',
        defaultValue: ''
    }, {
        name: 'bibliografia_titulo',
        defaultValue: ''
    }, {
        name: 'bibliografia_fechaedicion',
        defaultValue: ''
    }, {
        name: 'bibliografia_isbn',
        defaultValue: ''
    }, {
        name: 'bibliografia_editorial',
        defaultValue: ''
    }, {
        name: 'bibliografia_detalles',
        defaultValue: ''
    }, {
        name: 'bibliografia_categorias',
        defaultValue: ''
    }, {
        name: 'bibliografia_supervisado',
        defaultValue: ''
    }, {
        name: 'bibliografia_usuario_id',
        defaultValue: ''
    }, {
        name: 'usuario_login',
        defaultValue: ''
    }, {
        name: 'usuario_apellido1',
        defaultValue: ''
    }, {
        name: 'usuario_apellido2',
        defaultValue: ''
    }, {
        name: 'bibliografia_bloqueado',
        defaultValue: ''
    }, {
        name: 'bibliografia_ultimamod',
        defaultValue: ''
    }],
	associations: [
    {
        type: 'hasMany',
        model: 'RESCATE.model.Profesional',
        name: 'profesionales'
    }]
});
// model de Técnica
Ext.define('RESCATE.model.Tecnica', {
    extend: 'Ext.data.Model',
    idProperty: 'tecnica_id',
    fields: [
    {
        name: 'tecnica_id',
        defaultValue: ''
    }, {
        name: 'tecnicaprofesional_profesional_id',
        defaultValue: ''
    }, {
        name: 'tecnica_nombre',
        defaultValue: ''
    }, {
        name: 'tecnica_detalles',
        defaultValue: ''
    }, {
        name: 'tecnica_fecha',
        defaultValue: ''
    }, {
        name: 'tecnicaprofesional_detalles',
        defaultValue: ''
    }]
});
// model de Equipamiento
Ext.define('RESCATE.model.Equipamiento', {
    extend: 'Ext.data.Model',
    idProperty: 'equipamiento_id',
    fields: [
    {
        name: 'equipamiento_id',
        defaultValue: ''
    }, {
        name: 'equipamientoprofesional_profesional_id',
        defaultValue: ''
    }, {
        name: 'equipamiento_nombre',
        defaultValue: ''
    }, {
        name: 'equipamiento_detalles',
        defaultValue: ''
    }, {
        name: 'equipamiento_fecha',
        defaultValue: ''
    }, {
        name: 'equipamientoprofesional_detalles',
        defaultValue: ''
    }]
});
// model de Material
Ext.define('RESCATE.model.Material', {
    extend: 'Ext.data.Model',
    idProperty: 'material_id',
    fields: [
    {
        name: 'id',
        type: 'int'
    }, {
        name: 'idParent',
        defaultValue: ''
    }, {
        name: 'text',
        defaultValue: ''
    }, {
        name: 'leaf',
        defaultValue: true
    }, {
        name: 'material_id',
        defaultValue: ''
    }, {
        name: 'materialintervencion_intervencion_id',
        defaultValue: ''
    }, {
        name: 'material_nombre',
        defaultValue: ''
    }, {
        name: 'material_padre_id',
        defaultValue: ''
    }, {
        name: 'material_detalles',
        defaultValue: ''
    }, {
        name: 'materialintervencion_detalles',
        defaultValue: ''
    }]
});
// model de Procedimiento
Ext.define('RESCATE.model.Procedimiento', {
    extend: 'Ext.data.Model',
    idProperty: 'procedimiento_id',
    fields: [
    {
        name: 'id',
        type: 'int'
    }, {
        name: 'idParent',
        defaultValue: ''
    }, {
        name: 'text',
        defaultValue: ''
    }, {
        name: 'leaf',
        defaultValue: true
    }, {
        name: 'procedimiento_id',
        defaultValue: ''
    }, {
        name: 'procedimientointervencion_intervencion_id',
        defaultValue: ''
    }, {
        name: 'procedimiento_nombre',
        defaultValue: ''
    }, {
        name: 'procedimiento_padre_id',
        defaultValue: ''
    }, {
        name: 'procedimiento_detalles',
        defaultValue: ''
    }, {
        name: 'procedimientointervencion_detalles',
        defaultValue: ''
    }]
});
// model de Biencultural
Ext.define('RESCATE.model.Biencultural', {
    extend: 'Ext.data.Model',
    idProperty: 'biencultural_id',
    fields: [
    {
        name: 'biencultural_id',
        defaultValue: ''
    }, {
        name: 'bienculturalintervencion_intervencion_id',
        defaultValue: ''
    }, {
        name: 'biencultural_nombre',
        defaultValue: ''
    }, {
        name: 'biencultural_detalles',
        defaultValue: ''
    }, {
        name: 'bienculturalintervencion_detalles',
        defaultValue: ''
    }]
});
// model de Especialidad
Ext.define('RESCATE.model.Especialidad', {
    extend: 'Ext.data.Model',
    idProperty: 'especialidad_id',
    fields: [
    {
        name: 'especialidad_id',
        defaultValue: ''
    }, {
        name: 'especialidadprofesional_profesional_id',
        defaultValue: ''
    }, {
        name: 'especialidad_nombre',
        defaultValue: ''
    }, {
        name: 'especialidad_detalles',
        defaultValue: ''
    }, {
        name: 'especialidadprofesional_valor',
        defaultValue: ''
    }, {
        name: 'especialidadprofesional_detalles',
        defaultValue: ''
    }]
});
// model de Metodo
Ext.define('RESCATE.model.Metodo', {
    extend: 'Ext.data.Model',
    idProperty: 'metodo_id',
    fields: [
    {
        name: 'id',
        type: 'int'
    }, {
        name: 'idParent',
        defaultValue: ''
    }, {
        name: 'text',
        defaultValue: ''
    }, {
        name: 'leaf',
        defaultValue: true
    }, {
        name: 'metodo_id',
        defaultValue: ''
    }, {
        name: 'metodoobra_obra_id',
        defaultValue: ''
    }, {
        name: 'metodo_nombre',
        defaultValue: ''
    }, {
        name: 'metodo_padre_id',
        defaultValue: ''
    }, {
        name: 'metodo_detalles',
        defaultValue: ''
    }, {
        name: 'metodoobra_detalles',
        defaultValue: ''
    }]
});
// model de tipo documento
Ext.define('RESCATE.model.Tipo', {
    extend: 'Ext.data.Model',
    idProperty: 'tipo_id',
    fields: [
    {
        name: 'tipo_id',
        defaultValue: ''
    }, {
        name: 'tipodocumento_documento_id',
        defaultValue: ''
    }, {
        name: 'tipo_nombre',
        defaultValue: ''
    }, {
        name: 'tipo_detalles',
        defaultValue: ''
    }, {
        name: 'tipodocumento_detalles',
        defaultValue: ''
    }]
});
// model de Centro
Ext.define('RESCATE.model.Centro', {
    extend: 'Ext.data.Model',
    idProperty: 'centro_id',
    fields: [
    {
        name: 'centro_id',
        defaultValue: ''
    }, {
        name: 'centro_nombre',
        defaultValue: ''
    }, {
        name: 'centro_detalles',
        defaultValue: ''
    }, {
        name: 'centro_codigo',
        defaultValue: ''
    }, {
        name: 'centro_tipo',
        defaultValue: ''
    }]
});
// model de Colaborador
Ext.define('RESCATE.model.Colaborador', {
    extend: 'Ext.data.Model',
    idProperty: 'colaborador_colaborador_id',
    fields: [
    {
        name: 'colaborador_colaborador_id',
        defaultValue: ''
    }, {
        name: 'colaborador_profesional_id',
        defaultValue: ''
    }, {
        name: 'profesional_nombre',
        defaultValue: ''
    }, {
        name: 'profesional_apellido1',
        defaultValue: ''
    }, {
        name: 'profesional_apellido2',
        defaultValue: ''
    }, {
        name: 'colaborador_tipo',
        defaultValue: ''
    }, {
        name: 'colaborador_detalles',
        defaultValue: ''
    }]
});
// model de Intervencion
Ext.define('RESCATE.model.Intervencion', {
    extend: 'Ext.data.Model',
    idProperty: 'intervencion_id',
    fields: [
    {
        name: 'intervencion_id',
        defaultValue: ''
    }, {
        name: 'intervencion_nombre',
        defaultValue: ''
    }, {
        name: 'intervencion_detalles',
        defaultValue: ''
    }, {
        name: 'intervencion_principiosteoricos',
        defaultValue: ''
    }, {
        name: 'intervencion_descprocedimiento',
        defaultValue: ''
    }, {
        name: 'intervencion_estadoconservacion',
        defaultValue: ''
    }, {
        name: 'intervencion_obra_id',
        defaultValue: ''
    }, {
        name: 'obra_nombre',
        defaultValue: ''
    }, {
        name: 'intervencion_fechainicio',
        defaultValue: ''
    }, {
        name: 'intervencion_fechafin',
        defaultValue: ''
    }, {
        name: 'intervencion_supervisado',
        defaultValue: ''
    }, {
        name: 'intervencion_usuario_id',
        defaultValue: ''
    }, {
        name: 'usuario_login',
        defaultValue: ''
    }, {
        name: 'usuario_apellido1',
        defaultValue: ''
    }, {
        name: 'usuario_apellido2',
        defaultValue: ''
    }, {
        name: 'intervencion_bloqueado',
        defaultValue: ''
    }, {
        name: 'intervencion_ultimamod',
        defaultValue: ''
    }]
});
// model de Intervencion
Ext.define('RESCATE.model.IntervencionProfesional', {
    extend: 'Ext.data.Model',
    idProperty: 'intervencionprofesional_profesional_id',
    fields: [
    {
        name: 'intervencionprofesional_intervencion_id',
        defaultValue: ''
    }, {
        name: 'intervencionprofesional_profesional_id',
        defaultValue: ''
    }, {
        name: 'intervencionprofesional_detalles',
        defaultValue: ''
    }, {
        name: 'intervencionprofesional_cargo',
        defaultValue: ''
    }, {
        name: 'profesional_nombre',
        defaultValue: ''
    }, {
        name: 'profesional_apellido1',
        defaultValue: ''
    }, {
        name: 'profesional_apellido2',
        defaultValue: ''
    }, {
        name: 'profesional_tipo',
        defaultValue: ''
    }]
});
// model de Documento
Ext.define('RESCATE.model.Documento', {
    extend: 'Ext.data.Model',
    idProperty: 'documento_id',
    fields: [
    {
        name: 'documento_id',
        defaultValue: ''
    }, {
        name: 'usuario_nombre',
        defaultValue: ''
    }, {
        name: 'usuario_login',
        defaultValue: ''
    }, {
        name: 'usuario_apellido1',
        defaultValue: ''
    }, {
        name: 'usuario_apellido2',
        defaultValue: ''
    }, {
        name: 'documento_titulo',
        defaultValue: ''
    }, {
        name: 'documento_ref',
        defaultValue: ''
    }, {
        name: 'documento_archivo',
        defaultValue: ''
    }, {
        name: 'documento_tipo',
        defaultValue: ''
    }, {
        name: 'documento_enlace',
        defaultValue: ''
    }, {
        name: 'documento_miniatura',
        defaultValue: CONFIG.UrlRelArchivos + '/desconocido.png'
    }, {
        name: 'documento_descripcion',
        defaultValue: ''
    }, {
        name: 'documento_pais',
        defaultValue: 'España'
    }, {
        name: 'documento_provincia',
        defaultValue: ''
    }, {
        name: 'documento_poblacion',
        defaultValue: ''
    }, {
        name: 'documento_direccion',
        defaultValue: ''
    }, {
        name: 'documento_cordenadas',
        defaultValue: ''
    }, {
        name: 'profesional_nombre',
        defaultValue: ''
    }, {
        name: 'profesional_apellido1',
        defaultValue: ''
    }, {
        name: 'profesional_apellido2',
        defaultValue: ''
    }, {
        name: 'documento_fechainicial',
        defaultValue: ''
    }, {
        name: 'documento_fechafinal',
        defaultValue: ''
    }, {
        name: 'documento_supervisado',
        defaultValue: ''
    }, {
        name: 'documento_usuario_id',
        defaultValue: ''
    }, {
        name: 'documento_ultimamod',
        defaultValue: ''
    }, {
        name: 'documento_bloqueado',
        defaultValue: ''
    }]
});
// model de Documento
Ext.define('RESCATE.model.DocumentoProfesional', {
    extend: 'Ext.data.Model',
    idProperty: 'documentoprofesional_documento_id',
    fields: [
    {
        name: 'documentoprofesional_documento_id',
        defaultValue: ''
    }, {
        name: 'documentoprofesional_profesional_id',
        defaultValue: ''
    }, {
        name: 'documentoprofesional_detalles',
        defaultValue: ''
    }, {
        name: 'documento_titulo',
        defaultValue: ''
    }, {
        name: 'documento_autorapellido1',
        defaultValue: ''
    }, {
        name: 'documento_autorapellido2',
        defaultValue: ''
    }, {
        name: 'documento_autornombre',
        defaultValue: ''
    }, {
        name: 'documento_miniatura',
        defaultValue: ''
    }, {
        name: 'documento_archivo',
        defaultValue: ''
    }, {
        name: 'documento_tipo',
        defaultValue: ''
    }]
});
// model de Documento
Ext.define('RESCATE.model.DocumentoIntervencion', {
    extend: 'Ext.data.Model',
    idProperty: 'documentointervencion_documento_id',
    fields: [
    {
        name: 'documentointervencion_documento_id',
        defaultValue: ''
    }, {
        name: 'documentointervencion_intervencion_id',
        defaultValue: ''
    }, {
        name: 'documentointervencion_detalles',
        defaultValue: ''
    }, {
        name: 'documento_titulo',
        defaultValue: ''
    }, {
        name: 'documento_autorapellido1',
        defaultValue: ''
    }, {
        name: 'documento_autorapellido2',
        defaultValue: ''
    }, {
        name: 'documento_autornombre',
        defaultValue: ''
    }, {
        name: 'documento_miniatura',
        defaultValue: ''
    }, {
        name: 'documento_archivo',
        defaultValue: ''
    }, {
        name: 'documento_tipo',
        defaultValue: ''
    }]
});
// model de Documento
Ext.define('RESCATE.model.DocumentoObra', {
    extend: 'Ext.data.Model',
    idProperty: 'documentoobra_documento_id',
    fields: [
    {
        name: 'documentoobra_documento_id',
        defaultValue: ''
    }, {
        name: 'documentoobra_obra_id',
        defaultValue: ''
    }, {
        name: 'documentoobra_detalles',
        defaultValue: ''
    }, {
        name: 'documentoobra_portada',
        defaultValue: ''
    }, {
        name: 'documento_titulo',
        defaultValue: ''
    }, {
        name: 'documento_autorapellido1',
        defaultValue: ''
    }, {
        name: 'documento_autorapellido2',
        defaultValue: ''
    }, {
        name: 'documento_autornombre',
        defaultValue: ''
    }, {
        name: 'documento_miniatura',
        defaultValue: ''
    }, {
        name: 'documento_archivo',
        defaultValue: ''
    }, {
        name: 'documento_tipo',
        defaultValue: ''
    }]
});