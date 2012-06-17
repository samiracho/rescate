// clase estática donde me defino métodos que utilizaré en varios lugares. Así no los repito
Ext.define('RESCATE.store.Operaciones', {
    statics: {
        controlarExcepcion: function (proxy, response, operation, scope)
        {
            var me = scope;
            // desbloqueamos la interfaz
            Ext.getBody().unmask();
            // si la creación ha fallado descartamos los cambios en el store
            if (operation.action == 'create')
            {
                me.each(function (rec)
                {
                    if(rec)
					{
						if (rec.dirty)rec.reject();
						if (rec.phantom)me.remove(rec);
					}
                });
            }
            var json = Ext.decode(response.responseText);
            if (json)
            {
                // marcamos los errores en los campos que el servidor ha considerado inválidos
                if (me.formulario != null) me.formulario.getForm().markInvalid(json.errors);
                var mensaje = "";
                if (typeof json.errors == 'string') mensaje += json.errors;
                else
                try
                {
                    for (i = 0; i < json.errors.length; i++)
                    {
                        mensaje += json.errors[i]['msg'] + '<br />';
                    }
                }
                catch (err)
                {
                    mensaje += err.toString();
                };
                Ext.MessageBox.show(
                {
                    title: json.message,
                    msg: mensaje,
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.Msg.OK,
                    items: {
                        xtype: 'textfield',
                        fieldLabel: 'error'
                    }
                });
            }
            else
            {
                Ext.MessageBox.show(
                {
                    title: t('REMOTE EXCEPTION'),
                    msg: operation.error.status + " " + operation.error.statusText,
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.Msg.OK
                });
            }
            me.formulario = null;
        },
        eliminar: function (record, parametros, scope)
        {
            var me = scope;
            Ext.getBody().mask(t('loading'));
            Ext.Ajax.request(
            {
                scope: me,
                // para que apunte al store
                url: me.proxy.api.destroy ? me.proxy.api.destroy : me.proxy.urlDestroy,
                method: 'POST',
                timeout: 60000,
                params: parametros,
                success: function (response, request)
                { // recibe respuesta del servidor
                    var json = Ext.decode(response.responseText);
                    if (json)
                    {
                        if (json.success)
                        {
                            // si todo ha ido bien recargamos el store, así ya no aparecerá el registro borrado y sincronizamos la barra de paginación
                            me.load(me.lastOptions);
                            // mostramos confirmación al usuario
                            RESCATE.confirmacion();
                        }
                        else
                        {
                            // desbloqueamos la interfaz
                            Ext.getBody().unmask();
                            var mensaje = json.message;
                            if (typeof json.errors == 'string') mensaje += '<br />' + json.errors;
                            // mostramos el error
                            Ext.MessageBox.show(
                            {
                                title: t('REMOTE EXCEPTION'),
                                msg: mensaje,
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.Msg.OK
                            });
                        }
                    }
                    else
                    {
                        // desbloqueamos la interfaz
                        Ext.getBody().unmask();
                        // mostramos el error
                        Ext.MessageBox.show(
                        {
                            title: t('REMOTE EXCEPTION'),
                            msg: t('internal error'),
                            icon: Ext.MessageBox.ERROR,
                            buttons: Ext.Msg.OK
                        });
                    }
                },
                failure: function (response, request)
                { // no recibe respuesta del servidor
                    // desbloqueamos la interfaz
                    Ext.getBody().unmask();
                    // mostramos el mensaje de error
                    Ext.MessageBox.show(
                    {
                        title: t('REMOTE EXCEPTION'),
                        msg: response.responseText,
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.Msg.OK
                    });
                }
            });
        }
    }
});
// store generico del que heredan todos los demás. He hecho cambios para poder controlar mejor los errores
Ext.define('RESCATE.store.store', {
    extend: 'Ext.data.Store',
    remoteSort: true,
	// Referencia al form.
	// Si asociamos un formulario al store, en caso de que haya campos incorrectos, estos se marcarán en rojo.
	formulario: null,
    constructor: function (config)
    {
        var me = this;
        config = config || {
        };
        this.param = config.param;
        config.autoLoad = false;
        config.pageSize = 50;
        config.proxy = new Ext.data.proxy.Ajax(
        {
            pageParam: undefined,
            ventana: null,
            reader: {
                type: 'json',
                root: 'data',
                messageProperty: 'message'
            },
            writer: {
                type: 'json',
                writeAllFields: true,
                root: 'data'
            },
            listeners: {
                // para mostrar un mensaje de error en caso de que ocurra algún fallo
                exception: function (proxy, response, operation)
                {
					RESCATE.store.Operaciones.controlarExcepcion(proxy, response, operation, me);
                }
            }
        });
        this.callParent([config]);
    },
    // He hecho una función a parte para eliminar porque sino no puedo controlar los errores bien. 
    // Solo se borra la fila si el servidor nos indica operación exitosa, así si hay errores no tocamos el store, y no hay que liarse sincronizando, descartando cambios e.t.c
    eliminar: function (record, parametros)
    {
        RESCATE.store.Operaciones.eliminar(record, parametros, this);
    }
});

Ext.define('RESCATE.store.treeStore', {
    extend: 'Ext.data.TreeStore',
    // me he hecho una función para guardar los nodos porque estoy hasta los cojones del sync del treeStore
	// he sobrecargado getResponseData en el proxy para para poder leer los datos JSON anidados, y poder controlar los errores.
    url:'',
	urlAdd:'',
	urlDestroy:'',
	rootText:'',
	autoLoad: false,
	constructor: function(config) {
        var me = this;
		config = Ext.apply(config || {}, {
            model: me.model,
			/*folderSort: true,
			sorters: [{
				property: 'text',
				direction: 'ASC'
			}],*/
			root: {
				allowDrag: false,
				text: me.rootText,
				leaf: false,
				id: 0,
				expanded: true,
				/*Necesito especificar un array vacío para evitar que llame a load() una segunda vez. 4.1rc1 */
				children: []
			},
            proxy: {
                type: 'ajax',
				url: me.url,
				urlAdd: me.urlAdd,
				urlDestroy: me.urlDestroy,
                reader: {
					type: 'json',
					
					getResponseData: function(response) {
						var resp = Ext.decode(response.responseText);
						
						if (resp)
						{	
							if(resp.success)
							{
								// para pillar lo que esté dentro del array data de la respuesta json
								response.responseText = Ext.encode(resp.data);
								var data = Ext.data.reader.Json.prototype.getResponseData.call(this, response);
								return data;
							}
							else
							{				
								var mensaje = resp.message;
								if (typeof resp.errors == 'string') mensaje += '<br />' + resp.errors;
								
								// mostramos el error
								Ext.MessageBox.show(
								{
									title: t('REMOTE EXCEPTION'),
									msg: mensaje,
									icon: Ext.MessageBox.ERROR,
									buttons: Ext.Msg.OK
								});		
							}
						}
						else
						{
							// mostramos el error
							Ext.MessageBox.show(
							{
								title: t('REMOTE EXCEPTION'),
								msg: response.responseText,
								icon: Ext.MessageBox.ERROR,
								buttons: Ext.Msg.OK
							});
						}	
					}
				}
            }
        });

        this.callParent([config]);
    },
	guardarNodo: function (nodo, callback)
    {
        Ext.Ajax.request(
        {
            url: this.proxy.urlAdd,
            method: 'POST',
            timeout: 60000,
            params: nodo.data,
            success: function (response, request)
            { // recibe respuesta del servidor
                var json = Ext.decode(response.responseText);
				
                if (json)
                {
					if(json.success)
					{
						if (callback) callback();
					}
					else
					{
						var mensaje = json.message;
						if (typeof json.errors == 'string') mensaje += '<br />' + json.errors;
						
						// mostramos el error
						Ext.MessageBox.show(
						{
							title: t('REMOTE EXCEPTION'),
							msg: mensaje,
							icon: Ext.MessageBox.ERROR,
							buttons: Ext.Msg.OK
						});
					}
                }
                else
                {
                    // desbloqueamos la interfaz
                    Ext.getBody().unmask();
                    // mostramos el error
                    Ext.MessageBox.show(
                    {
                        title: t('REMOTE EXCEPTION'),
                        msg: t('internal error'),
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.Msg.OK
                    });
                }
            },
            failure: function (response, request)
            { // no recibe respuesta del servidor, o es errónea
                // desbloqueamos la interfaz
                Ext.getBody().unmask();
                // mostramos el mensaje de error
                Ext.MessageBox.show(
                {
                    title: t('REMOTE EXCEPTION'),
                    msg: response.responseText,
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.Msg.OK
                });
            }
        });
    },
    eliminar: function (record, parametros)
    {
        RESCATE.store.Operaciones.eliminar(record, parametros, this);
    }
});

// treestore de tipo material
Ext.define('RESCATE.store.Material', {
    alias: 'widget.store.Material',
    extend: 'RESCATE.store.treeStore',
    model: 'RESCATE.model.Material',
	url: 'datos/material.php?action=read',
	urlAdd: 'datos/material.php?action=add',
	urlDestroy: 'datos/material.php?action=destroy',
	rootText:t('Materials')
});

// treestore de tipo metodo
Ext.define('RESCATE.store.Metodo', {
    alias: 'widget.store.Metodo',
    extend: 'RESCATE.store.treeStore',
    model: 'RESCATE.model.Metodo',
    url: 'datos/metodo.php?action=read',
    urlAdd: 'datos/metodo.php?action=add',
    urlDestroy: 'datos/metodo.php?action=destroy',
	rootText:t('Technnics')
});

// treestore de tipo procedimiento
Ext.define('RESCATE.store.Procedimiento', {
    alias: 'widget.store.Procedimiento',
    extend: 'RESCATE.store.treeStore',
    model: 'RESCATE.model.Procedimiento',
    url: 'datos/procedimiento.php?action=read',
    urlAdd: 'datos/procedimiento.php?action=add',
    urlDestroy: 'datos/procedimiento.php?action=destroy',
	rootText:t('Procedimientos')	
});

// store con anyos
Ext.define('RESCATE.store.Anyo', {
	alias: 'widget.store.Anyo',
    extend: 'Ext.data.Store',
	inicio:1000,
	fields : [
		{name : 'anyo', type : 'string'}
	],
	data : [],
	constructor: function (config)
	{
		var currentTime = new Date();
		var year = currentTime.getFullYear();
		var years = [];
		var y = config.inicio >-1 ? config.inicio : this.inicio;

		while (year>=y){
		   years.push({'anyo':year});
		   year--;
		}
	
		config.data = years;
		
		
		this.callParent([config]);
	}
});

// store de tipo Categorías de Materiales
Ext.define('RESCATE.store.CategoriaMaterial', {
    alias: 'widget.store.CategoriaMaterial',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.idNombre',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/material.php?action=readCats'
        }
    }
});
// store de tipo material-intervencion
Ext.define('RESCATE.store.MaterialIntervencion', {
    alias: 'widget.store.MaterialIntervencion',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Material',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/material.php?action=read',
            update: 'datos/material.php?action=addMaterialPro',
            create: 'datos/material.php?action=addMaterialPro',
            destroy: 'datos/material.php?action=destroyMaterialPro'
        }
    }
});

// store de tipo Categorías de Procedimientoes
Ext.define('RESCATE.store.CategoriaProcedimiento', {
    alias: 'widget.store.CategoriaProcedimiento',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.idNombre',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/procedimiento.php?action=readCats'
        }
    }
});
// store de tipo procedimiento-intervencion
Ext.define('RESCATE.store.ProcedimientoIntervencion', {
    alias: 'widget.store.ProcedimientoIntervencion',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Procedimiento',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/procedimiento.php?action=read',
            update: 'datos/procedimiento.php?action=addProcedimientoPro',
            create: 'datos/procedimiento.php?action=addProcedimientoPro',
            destroy: 'datos/procedimiento.php?action=destroyProcedimientoPro'
        }
    }
});
Ext.define('RESCATE.store.Filtro', {
    extend: 'Ext.data.Store',
    alias: 'widget.store.Filtro',
    model: 'RESCATE.model.Filtro'
});
Ext.define('RESCATE.store.Sexo', {
    extend: 'Ext.data.Store',
    alias: 'widget.store.Sexo',
    model: 'RESCATE.model.idNombre',
    data: [
    {
        'id': 'Hombre',
        'nombre': t('Man')
    }, {
        'id': 'Mujer',
        'nombre': t('Woman')
    }]
});
Ext.define('RESCATE.store.Operador', {
    extend: 'Ext.data.Store',
    alias: 'widget.store.Operador',
    model: 'RESCATE.model.idNombre',
    data: [
    {
        'id': '1',
        'nombre': t('AND')
    }, {
        'id': '2',
        'nombre': t('AND NOT')
    }, {
        'id': '3',
        'nombre': t('OR')
    }, {
        'id': '4',
        'nombre': t('OR NOT')
    }]
});
Ext.define('RESCATE.store.Comparador', {
    extend: 'Ext.data.Store',
    alias: 'widget.store.Comparador',
    model: 'RESCATE.model.idNombre',
    data: [
    {
        'id': '1',
        'nombre': t('=')
    }, {
        'id': '2',
        'nombre': t('Distinct')
    }, {
        'id': '3',
        'nombre': t('LIKE')
    }, {
        'id': '4',
        'nombre': t('NOT LIKE')
    }, {
        'id': '5',
        'nombre': t('>')
    }, {
        'id': '6',
        'nombre': t('<')
    }]
});
// store de tipo rol
Ext.define('RESCATE.store.Rol', {
    alias: 'widget.store.Rol',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Rol',
    remoteSort: false,
    // heredamos casi todas las propiedades del store genérico RESCATE.store.store
    // Como no desciende de component no tenemos la función initComponent y tenemos que definir sus variables en el constructor
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/rol.php?action=read',
            update: 'datos/rol.php?action=add',
            create: 'datos/rol.php?action=add',
            destroy: 'datos/rol.php?action=destroy'
        }
    }
});
// store de tipo usuario
Ext.define('RESCATE.store.Usuario', {
    alias: 'widget.store.Usuario',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Usuario',
    // heredamos casi todas las propiedades del store genérico RESCATE.store.store
    // Como no desciende de component no tenemos la función initComponent y tenemos que definir sus variables en el constructor
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/usuario.php?action=read',
            update: 'datos/usuario.php?action=add',
            create: 'datos/usuario.php?action=add',
            destroy: 'datos/usuario.php?action=destroy'
        }
    }
});
// store de tipo profesional
Ext.define('RESCATE.store.Profesional', {
    alias: 'widget.store.Profesional',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Profesional',
    // heredamos casi todas las propiedades del store genérico RESCATE.store.store
    // Como no desciende de component no tenemos la función initComponent y tenemos que definir sus variables en el constructor
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/profesional.php?action=read',
            update: 'datos/profesional.php?action=add',
            create: 'datos/profesional.php?action=add',
            destroy: 'datos/profesional.php?action=destroy'
        }
    }
});
// store de tipo bibliografia
Ext.define('RESCATE.store.Bibliografia', {
    alias: 'widget.store.Bibliografia',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Bibliografia',
    // heredamos casi todas las propiedades del store genérico RESCATE.store.store
    // Como no desciende de component no tenemos la función initComponent y tenemos que definir sus variables en el constructor
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/bibliografia.php?action=read',
            update: 'datos/bibliografia.php?action=add',
            create: 'datos/bibliografia.php?action=add',
            destroy: 'datos/bibliografia.php?action=destroy'
        }
    }
});

// store de tipo ProfesionalBibliografia
Ext.define('RESCATE.store.ProfesionalBibliografia', {
    alias: 'widget.store.ProfesionalBibliografia',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.ProfesionalBibliografia',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/bibliografia.php?action=readProBib',
            update: 'datos/bibliografia.php?action=addProBib',
            create: 'datos/bibliografia.php?action=addProBib',
            destroy: 'datos/bibliografia.php?action=destroyProBib'
        }
    }
});

// store de tipo relaciones entre bibliografias y profesionales
Ext.define('RESCATE.store.TiposProfesionalBibliografia', {
    alias: 'widget.store.TiposProfesionalBibliografia',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.idNombre',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/bibliografia.php?action=readTiposProBib'
        }
    }
});

// store de tipo proObra
Ext.define('RESCATE.store.ProfesionalObra', {
    alias: 'widget.store.ProfesionalObra',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.ProfesionalObra',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/obra.php?action=readProObra',
            update: 'datos/obra.php?action=addProObra',
            create: 'datos/obra.php?action=addProObra',
            destroy: 'datos/obra.php?action=destroyProObra'
        }
    }
});

// store de tipo proDocumento
Ext.define('RESCATE.store.ProfesionalDocumento', {
    alias: 'widget.store.ProfesionalDocumento',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.ProfesionalDocumento',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/documento.php?action=readProDoc',
            update: 'datos/documento.php?action=addProDoc',
            create: 'datos/documento.php?action=addProDoc',
            destroy: 'datos/documento.php?action=destroyProDoc'
        }
    }
});

// store de tipo asociacion
Ext.define('RESCATE.store.Asociacion', {
    alias: 'widget.store.Asociacion',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Asociacion',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/asociacion.php?action=read',
            update: 'datos/asociacion.php?action=add',
            create: 'datos/asociacion.php?action=add',
            destroy: 'datos/asociacion.php?action=destroy'
        }
    }
});

// store de tipo ubicacion
Ext.define('RESCATE.store.Ubicacion', {
    alias: 'widget.store.Ubicacion',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Ubicacion',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/ubicacion.php?action=read',
            update: 'datos/ubicacion.php?action=add',
            create: 'datos/ubicacion.php?action=add',
            destroy: 'datos/ubicacion.php?action=destroy'
        }
    }
});
// store de tipo ubicacion-obra
Ext.define('RESCATE.store.UbicacionObra', {
    alias: 'widget.store.UbicacionObra',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Ubicacion',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/ubicacion.php?action=read',
            update: 'datos/ubicacion.php?action=addUbicacionObra',
            create: 'datos/ubicacion.php?action=addUbicacionObra',
            destroy: 'datos/ubicacion.php?action=destroyUbicacionObra'
        }
    }
});
// store de tipo asociacion-profesional
Ext.define('RESCATE.store.AsociacionProfesional', {
    alias: 'widget.store.AsociacionProfesional',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Asociacion',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/asociacion.php?action=read',
            update: 'datos/asociacion.php?action=addAsociacionPro',
            create: 'datos/asociacion.php?action=addAsociacionPro',
            destroy: 'datos/asociacion.php?action=destroyAsociacionPro'
        }
    }
});
// store de tipo tecnica
Ext.define('RESCATE.store.Tecnica', {
    alias: 'widget.store.Tecnica',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Tecnica',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/tecnica.php?action=read',
            update: 'datos/tecnica.php?action=add',
            create: 'datos/tecnica.php?action=add',
            destroy: 'datos/tecnica.php?action=destroy'
        }
    }
});
// store de tipo tecnica-profesional
Ext.define('RESCATE.store.TecnicaProfesional', {
    alias: 'widget.store.TecnicaProfesional',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Tecnica',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/tecnica.php?action=read',
            update: 'datos/tecnica.php?action=addTecnicaPro',
            create: 'datos/tecnica.php?action=addTecnicaPro',
            destroy: 'datos/tecnica.php?action=destroyTecnicaPro'
        }
    }
});
// store de tipo especialidad
Ext.define('RESCATE.store.Especialidad', {
    alias: 'widget.store.Especialidad',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Especialidad',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/especialidad.php?action=read',
            update: 'datos/especialidad.php?action=add',
            create: 'datos/especialidad.php?action=add',
            destroy: 'datos/especialidad.php?action=destroy'
        }
    }
});
// store de tipo especialidad-profesional
Ext.define('RESCATE.store.EspecialidadProfesional', {
    alias: 'widget.store.EspecialidadProfesional',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Especialidad',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/especialidad.php?action=read',
            update: 'datos/especialidad.php?action=addEspecialidadPro',
            create: 'datos/especialidad.php?action=addEspecialidadPro',
            destroy: 'datos/especialidad.php?action=destroyEspecialidadPro'
        }
    }
});
// store de tipo Categorías de Métodos
Ext.define('RESCATE.store.CategoriaMetodo', {
    alias: 'widget.store.CategoriaMetodo',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.idNombre',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/metodo.php?action=readCats'
        }
    }
});

// store de tipo metodo-obra
Ext.define('RESCATE.store.MetodoObra', {
    alias: 'widget.store.MetodoObra',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Metodo',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/metodo.php?action=read',
            update: 'datos/metodo.php?action=addMetodoObra',
            create: 'datos/metodo.php?action=addMetodoObra',
            destroy: 'datos/metodo.php?action=destroyMetodoObra'
        }
    }
});
// store de tipo tipo
Ext.define('RESCATE.store.Tipo', {
    alias: 'widget.store.Tipo',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Tipo',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/tipo.php?action=read',
            update: 'datos/tipo.php?action=add',
            create: 'datos/tipo.php?action=add',
            destroy: 'datos/tipo.php?action=destroy'
        }
    }
});
// store de tipo tipo-documento
Ext.define('RESCATE.store.TipoDocumento', {
    alias: 'widget.store.TipoDocumento',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Tipo',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/tipo.php?action=read',
            update: 'datos/tipo.php?action=addTipoDocumento',
            create: 'datos/tipo.php?action=addTipoDocumento',
            destroy: 'datos/tipo.php?action=destroyTipoDocumento'
        }
    }
});
// store de tipo reconocimiento
Ext.define('RESCATE.store.Reconocimiento', {
    alias: 'widget.store.Reconocimiento',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Reconocimiento',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/reconocimiento.php?action=read',
            update: 'datos/reconocimiento.php?action=add',
            create: 'datos/reconocimiento.php?action=add',
            destroy: 'datos/reconocimiento.php?action=destroy'
        }
    }
});
// store de tipo centro
Ext.define('RESCATE.store.Centro', {
    alias: 'widget.store.Centro',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Centro',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/centro.php?action=read',
            update: 'datos/centro.php?action=add',
            create: 'datos/centro.php?action=add',
            destroy: 'datos/centro.php?action=destroy'
        }
    }
});
// store de tipo Localizacion
Ext.define('RESCATE.store.Pais', {
    alias: 'widget.store.Pais',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.idNombre',
    // heredamos casi todas las propiedades del store genérico RESCATE.store.store
    // Como no desciende de component no tenemos la función initComponent y tenemos que definir sus variables en el constructor
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/datos.php?action=readPais'
        }
    }
});
// store de tipo Localizacion
Ext.define('RESCATE.store.Provincia', {
    alias: 'widget.store.Provincia',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.idNombre',
    // heredamos casi todas las propiedades del store genérico RESCATE.store.store
    // Como no desciende de component no tenemos la función initComponent y tenemos que definir sus variables en el constructor
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/datos.php?action=readProvincia'
        }
    }
});
// store de tipo Localizacion
Ext.define('RESCATE.store.Poblacion', {
    alias: 'widget.store.Poblacion',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.idNombre',
    // heredamos casi todas las propiedades del store genérico RESCATE.store.store
    // Como no desciende de component no tenemos la función initComponent y tenemos que definir sus variables en el constructor
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/datos.php?action=readPoblacion'
        }
    }
});
// store de tipo Tipos de centros
Ext.define('RESCATE.store.TiposCentros', {
    alias: 'widget.store.TiposCentros',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.idNombre',
    // heredamos casi todas las propiedades del store genérico RESCATE.store.store
    // Como no desciende de component no tenemos la función initComponent y tenemos que definir sus variables en el constructor
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/centro.php?action=readTiposCentros'
        }
    }
});
// store de tipo Tipos de colaboradores
Ext.define('RESCATE.store.TiposColaboradores', {
    alias: 'widget.store.TiposColaboradores',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.idNombre',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/colaborador.php?action=readTiposColaboradores'
        }
    }
});
// store de tipo gargos en intervenciones
Ext.define('RESCATE.store.CargosIntervencionProfesional', {
    alias: 'widget.store.CargosIntervencionProfesional',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.idNombre',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/intervencion.php?action=readCargosIntervencionPro'
        }
    }
});
// store de tipo Tipos de documentos
Ext.define('RESCATE.store.TiposDocumentos', {
    alias: 'widget.store.TiposDocumentos',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.idNombre',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/documento.php?action=readTiposDocumentos'
        }
    }
});
// store de tipo Creador. ( Lo utilizo para obtener el creador de un registro a partir de su id)
Ext.define('RESCATE.store.Creador', {
    alias: 'widget.store.Creador',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.idNombre',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/datos.php?action=readCreador'
        }
    }
});
// store de tipo Formacion
Ext.define('RESCATE.store.Formacion', {
    alias: 'widget.store.Formacion',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Formacion',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/formacion.php?action=read',
            update: 'datos/formacion.php?action=add',
            create: 'datos/formacion.php?action=add',
            destroy: 'datos/formacion.php?action=destroy'
        }
    }
});
// store de tipo Formacion
Ext.define('RESCATE.store.Cargo', {
    alias: 'widget.store.Cargo',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Cargo',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/cargo.php?action=read',
            update: 'datos/cargo.php?action=add',
            create: 'datos/cargo.php?action=add',
            destroy: 'datos/cargo.php?action=destroy'
        }
    }
});
// store de tipo Formacion
Ext.define('RESCATE.store.Noticia', {
    alias: 'widget.store.Noticia',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Noticia',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        this.pageSize = 10;
        config.proxy.api =
        {
            read: 'datos/noticia.php?action=read',
            update: 'datos/noticia.php?action=add',
            create: 'datos/noticia.php?action=add',
            destroy: 'datos/noticia.php?action=destroy'
        }
    }
});
// store de tipo Colaborador
Ext.define('RESCATE.store.Colaborador', {
    alias: 'widget.store.Colaborador',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Colaborador',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/colaborador.php?action=read',
            update: 'datos/colaborador.php?action=add',
            create: 'datos/colaborador.php?action=add',
            destroy: 'datos/colaborador.php?action=destroy'
        }
    }
});
// store de tipo Obra
Ext.define('RESCATE.store.Obra', {
    alias: 'widget.store.Obra',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Obra',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/obra.php?action=read',
            update: 'datos/obra.php?action=add',
            create: 'datos/obra.php?action=add',
            destroy: 'datos/obra.php?action=destroy'
        }
    }
});
// store de tipo Intervencion
Ext.define('RESCATE.store.Intervencion', {
    alias: 'widget.store.Intervencion',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Intervencion',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/intervencion.php?action=read',
            update: 'datos/intervencion.php?action=add',
            create: 'datos/intervencion.php?action=add',
            destroy: 'datos/intervencion.php?action=destroy'
        }
    }
});
// store de tipo Intervencionprofesional
Ext.define('RESCATE.store.IntervencionProfesional', {
    alias: 'widget.store.IntervencionProfesional',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.IntervencionProfesional',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/intervencion.php?action=readIntPro',
            update: 'datos/intervencion.php?action=addIntPro',
            create: 'datos/intervencion.php?action=addIntPro',
            destroy: 'datos/intervencion.php?action=destroyIntPro'
        }
    }
});
// store de tipo Documentoprofesional
Ext.define('RESCATE.store.DocumentoProfesional', {
    alias: 'widget.store.DocumentoProfesional',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.DocumentoProfesional',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/documento.php?action=readDocPro',
            update: 'datos/documento.php?action=addDocPro',
            create: 'datos/documento.php?action=addDocPro',
            destroy: 'datos/documento.php?action=destroyDocPro'
        }
    }
});
// store de tipo Documentointervencion
Ext.define('RESCATE.store.DocumentoIntervencion', {
    alias: 'widget.store.DocumentoIntervencion',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.DocumentoIntervencion',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/documento.php?action=readDocInt',
            update: 'datos/documento.php?action=addDocInt',
            create: 'datos/documento.php?action=addDocInt',
            destroy: 'datos/documento.php?action=destroyDocInt'
        }
    }
});
// store de tipo Documentoobra
Ext.define('RESCATE.store.DocumentoObra', {
    alias: 'widget.store.DocumentoObra',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.DocumentoObra',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        config.proxy.api =
        {
            read: 'datos/documento.php?action=readDocObr',
            update: 'datos/documento.php?action=addDocObr',
            create: 'datos/documento.php?action=addDocObr',
            destroy: 'datos/documento.php?action=destroyDocObr'
        }
    }
});
// store de tipo Obra
Ext.define('RESCATE.store.Documento', {
    alias: 'widget.store.Documento',
    extend: 'RESCATE.store.store',
    model: 'RESCATE.model.Documento',
    constructor: function (config)
    {
        this.callParent([config]);
        this.param = config.param;
        this.pageSize = 10;
        config.proxy.api =
        {
            read: 'datos/documento.php?action=read',
            update: 'datos/documento.php?action=add',
            create: 'datos/documento.php?action=add',
            destroy: 'datos/documento.php?action=destroy'
        }
    }
});