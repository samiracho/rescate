/*
	var numEvs = 0;
	Ext.util.Observable.prototype.fireEvent =Ext.Function.createInterceptor(Ext.util.Observable.prototype.fireEvent, function(evt) {
		numEvs++;
		a=arguments;console.log(this,'fired event nº ' +numEvs,evt,'with args',Array.prototype.slice.call(a,1,a.length));
		
	});
	*/

// Punto de partida de la aplicacion
Ext.application(
{
    name: 'RESCATE',
    launch: function ()
    {
        if (CONFIG.GuardarEstado)
        {
            if (CONFIG.GuardarEstadoEnSrv)
            {
                var hp = Ext.create('Ext.ux.state.HttpProvider', {
                    url: 'datos/usuario.php',
                    user: 'XXXXX',
                    session: 'e1',
                    id: 'pippo',
                    readBaseParams: {
                        action: 'readState'
                    },
                    saveBaseParams: {
                        action: 'saveState'
                    },
                    autoRead: false
                });
                Ext.state.Manager.setProvider(hp);
                Ext.state.Manager.getProvider().initState(CONFIG.DatosEstado);
            }
            else
            {
                Ext.state.Manager.setProvider(Ext.create('Ext.state.CookieProvider'), {
                    expires: new Date(new Date().getTime() + (CONFIG.EstadoTTL)) //30 days
                });
            }
            Ext.Component.prototype.stateful = false;
        }
        //borramos la máscara del body	
        RESCATE.quitarMascara();
        Ext.create('widget.viewportPrincipal', {
        });
    }
});

Ext.define('RESCATE.Viewport', {
    alias: 'widget.viewportPrincipal',
    extend: 'Ext.Viewport',
    layout: 'border',
    autoshow: false,
    itemId: 'viewportPrincipal',
    defaults: {
        bodyStyle: 'font-size:12px'
    },
    initComponent: function ()
    {
        var me = this;
        Ext.apply(this, {
            items: [
            {
                xtype: 'container',
                html: '<h1>' + t('RESCATE CONTROL PANEL') + '</h1><span style="font-size:9px;color:#fff">&nbsp;Rev ' + CONFIG.rev + '</span>',
                region: 'north',
                height: 30,
                padding: 4,
                border: 0,
                cls: 'cabecera',
                items: [
                {
                    width: 90,
                    xtype: 'button',
                    text: t('Logout'),
                    iconCls: 'iconoSalir',
                    cls: 'botonFormulario',
                    itemId: 'cerrarSesion',
                    handler: this.onExit
                }]
            }, {
                layout: 'border',
                xtype: 'panel',
                itemId: 'panelLateral',
                region: 'west',
                border: 0,
                split: true,
                collapsible: true,
                animCollapse: true,
                margin: '2 0 2 2',
                stateId: 'statePanelOpcionesMenuPrincipal',
                stateful: true,
                title: t('Options'),
                width: 240,
                items: [
                {
                    xtype: 'panelMenuPrincipal'
                }, {
                    xtype: 'panelAyuda'
                }]
            }, {
                xtype: 'container',
                layout: 'fit',
                region: 'center',
				margin:2,
                border: 0,
                listeners: {
					afterrender: function ()
                    {
                        var urlParam = Ext.Object.fromQueryString(location.search);
                        var nombrePanel = urlParam.opcion ? urlParam.opcion : 'Inicio';
                        var opcionesPosibles =
                        {
                            'Inicio': 'container.Dashboard',
                            'Usuario': 'panel.Usuario',
                            'Noticia': 'panel.Noticia',
                            'Restaurador': 'panel.Restaurador',
                            'Especialista': 'panel.Especialista',
                            'Otros': 'panel.Otros',
                            'Obra': 'panel.Obra',
                            'Intervencion': 'panel.Intervencion',
                            'Documento': 'panel.Documento',
                            'Bibliografia': 'grid.Bibliografia',
                            'Especialidad': 'panel.EspecialidadLista',
                            'Tecnica': 'panel.TecnicaLista',
                            'TipoDocumento': 'panel.TipoLista',
                            'Ubicacion': 'panel.UbicacionLista'
                        };
                        var nPanel = opcionesPosibles[nombrePanel] ? opcionesPosibles[nombrePanel] : 'panel.Inicio';
                        var componente = Ext.create('RESCATE.' + nPanel, {
                        });
                        this.add(componente);
                        // Para colorear el link seleccionado			
                        // primero quitamos la clase selected
                        var domElement = me.down('panelMenuPrincipal').getEl();
                        var link = domElement.query('a.selected')[0];
                        if (link)
                        {
                            link.className = link.className.replace('selected', '');
                        }
                        // luego agregamos la clase selected al enlace sobre el que se ha hecho click
                        // si no coincide ninguno, resaltamos Inicio
                        var selectedLink = domElement.query('a[href$=' + nombrePanel + ']')[0];
                        if (selectedLink)
                        {
                            selectedLink.className = selectedLink.className + " selected";
                        }
                        else
                        {
                            domElement.query('a')[0].className += " selected";
                        }
                        // lanzamos el evento activate del componente que acabamos de agregar
                        componente.fireEvent('activate', componente);
                    }
                },single: true
            }]
        });
        // llamamos a la función initComponent de su superclase
        this.callParent(arguments);
    },
    // funcion que se ejecuta al hacer click sobre el botón salir
    onExit: function ()
    {
        Ext.MessageBox.show(
        {
            title: t('Logout'),
            msg: t('Do you want to exit and close the session?'),
            buttons: Ext.MessageBox.YESNO,
            fn: function (buttonId)
            {
                switch (buttonId)
                {
                case 'no':
                    break;
                case 'yes':
                    window.location.href = 'index.php?logout=1';
                    break;
                }
            },
            scope: this
        });
    }
});
Ext.define('RESCATE.panelMenuPrincipal', {
    alias: 'widget.panelMenuPrincipal',
    extend: 'Ext.Panel',
    title: '',
    region: 'north',
    height: 420,
    minHeight: 420,
    bodyPadding: 4,
    split: true,
    initComponent: function ()
    {
        var menu = '<div class="menuPrincial"><div class="grupoOpciones">Principal</div><a class="iconoInicio" href="index.php">Inicio</a>';
        if (CONFIG.perms.administrar_usuarios) menu += '<a class="iconoUsuario" href="index.php?opcion=Usuario">' + t('Users') + '</a>';
        if (CONFIG.perms.administrar_noticias) menu += '<a class="iconoNoticia" href="index.php?opcion=Noticia">' + t('News') + '</a>';
        menu += '<div class="grupoOpciones">Base de Datos</div>';
        if (CONFIG.perms.agregareditar_registros_propios)
        {
            menu += '<a class="iconoRestaurador" href="index.php?opcion=Restaurador">' + t('Restaurators') + '</a>';
            menu += '<a class="iconoIntervencion" href="index.php?opcion=Intervencion">' + t('Interventions') + '</a>';
            menu += '<a class="iconoObra" href="index.php?opcion=Obra">' + t('Works Of Art') + '</a>';
            menu += '<a class="iconoDocumento" href="index.php?opcion=Documento">' + t('Documents') + '</a>';
            menu += '<a class="iconoEspecialista" href="index.php?opcion=Especialista">' + t('Specialists') + '</a>';
            menu += '<a class="iconoColaborador" href="index.php?opcion=Otros">' + t('Partners') + '</a>';
        }
        if (CONFIG.perms.administrar_bibliografias || CONFIG.perms.agregareditar_registros_propios)
        {
            menu += '<a class="iconoBibliografia" href="index.php?opcion=Bibliografia">' + t('Bibliography') + '</a>';
        }
        if (CONFIG.perms.administrar_especialidades || CONFIG.perms.administrar_tecnicas || CONFIG.perms.administrar_tipos || CONFIG.perms.administrar_ubicaciones)
        {
            menu += '<div class="grupoOpciones">Administración</div>';
            if (CONFIG.perms.administrar_especialidades)
            {
                menu += '<a class="iconoEspecialidad" href="index.php?opcion=Especialidad">' + t('Specialities') + '</a>';
            }
            if (CONFIG.perms.administrar_tecnicas)
            {
                menu += '<a class="iconoTecnica" href="index.php?opcion=Tecnica">' + t('Restauration Technnics') + '</a>';
            }
            if (CONFIG.perms.administrar_tipos)
            {
                menu += '<a class="iconoDocumento" href="index.php?opcion=TipoDocumento">' + t('Document Types') + '</a>';
            }
            if (CONFIG.perms.administrar_ubicaciones)
            {
                menu += '<a class="iconoPin" href="index.php?opcion=Ubicacion">' + t('Positions') + '</a>';
            }
        }
        this.html = menu + '</div>';
        this.callParent(arguments);
    }
});
Ext.define('RESCATE.panelAyuda', {
    alias: 'widget.panelAyuda',
    extend: 'Ext.Container',
    title: t('Help'),
    itemId: 'panelAyuda',
    region: 'center',
    autoScroll: true,
    autoLoad: {
        url: 'idiomas/' + t('EN') + '/hInicio.html',
        scripts: true,
        disableCaching: false
    }
});


/*-------------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------ COMPONENTES PERSONALIZADOS -----------------------------------------
---------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Creates a panel with a webcam widget. The webcam widget is
 * a flash (jpegcam).
 */
Ext.define('RESCATE.panel.Webcam', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.Webcam',
    initComponent: function ()
    {
        var me = this;
        me.takePhotoButton = Ext.create("Ext.button.Button", {
            text: t('Take picture and upload'),
            iconCls: 'iconoWebcam',
            handler: me.takePhoto,
            cls: 'botonFormulario',
            scope: me
        });
        me.dockedItems = Ext.create("Ext.container.Container", {
            dock: 'bottom',
            padding: 4,
            items: [
            {
                xtype: 'button',
                iconCls: 'iconoConfig',
                text: t('Config'),
                handler: me.configWebCam,
                style: 'float:left',
                tooltip: t("Config webcam")
            }, {
                xtype: 'button',
                text: t('Cancel'),
                cls: 'botonFormulario',
                handler: function ()
                {
                    me.up('window').close()
                }
            },
            me.takePhotoButton]
        });
        // Render the SWF
        me.on("afterrender", me.renderWebcam, me.configButton);
        // Fires when the image upload is complete
        me.addEvents("uploadComplete");
        me.callParent();
    },
    /**
     * Renders the webcam swf.
     * @param e The element for this component
     */
    renderWebcam: function (e)
    {
        webcam.set_swf_url(CONFIG.UrlRelAplicacion + "/swf/webcam.swf");
        webcam.set_quality(100);
        webcam.set_api_url(CONFIG.UrlRelAplicacion + "/datos/documento.php?action=webcam&documento_id=" + this.documentoId);
        webcam.set_shutter_sound(true, CONFIG.UrlRelAplicacion + "/swf/shutter.mp3");
        webcam.set_hook('onComplete', Ext.bind(this.onUploadComplete, this));
        e.body.insertHtml('beforeEnd', webcam.get_html(640, 480, 640, 480));
    },
    configWebCam: function ()
    {
        webcam.configure('camera');
    },
    /**
     * Takes a photo using the webcam.
     */
    takePhoto: function ()
    {
        webcam.snap();
        this.takePhotoButton.disable();
        this.takePhotoButton.setText(t("Uploading..."));
    },
    /**
     * Called when the upload is complete. Resumes webcam operation
     * and fires the event. 'uploadComplete'
     * @param message	The server side message
     */
    onUploadComplete: function (message)
    {
        var response = Ext.decode(message);
        webcam.reset();
        this.fireEvent("uploadComplete", response);
    }
});
Ext.define('RESCATE.panel.direccion', {
    alias: 'widget.panelDireccion',
    extend: 'Ext.container.Container',
    paisName: '',
    provinciaName: '',
    poblacionName: '',
    direccionName: '',
    cordenadasName: '',
    listeners: {
        activate: function (component, options)
        {
            var mapa = component.down('gmappanel');
            // lanzamos el evento activate
            mapa.fireEvent('activate', mapa);
            this.CargarDatos(component);
        },
        resize: function (component, w, h)
        {
            component.down('gmappanel').setSize(w, h, false);
        },
		afterrender: function(component)
		{
			var comboPais = component.down('combobox');
			var comboProvincia = comboPais.next('combobox');
			var comboPoblacion = comboProvincia.next('combobox');
			var fieldPais = component.down('#campoNombrePais'); // Tengo que guardar el pais en un campo de texto, sino no carga el valor en el combobox, no se porqué
			var fieldProvincia = component.down('#campoNombreProvincia');
			var fieldPoblacion = component.down('#campoNombrePoblacion');
			
			comboPoblacion.store.on('load', function (store)
			{
				comboPoblacion.suspendEvents();
				comboPoblacion.setValue(fieldPoblacion.getValue());
				comboPoblacion.valueNotFoundText = fieldPoblacion.getValue();
				store.cargado = true;
				comboPoblacion.resumeEvents();
			});
			comboProvincia.store.on('load', function (store)
			{
				comboProvincia.suspendEvents();
				comboProvincia.setValue(fieldProvincia.getValue());
				comboProvincia.valueNotFoundText = fieldProvincia.getValue();
				comboProvincia.resumeEvents();
			});
			comboPais.store.on('load', function (store)
			{
				comboPais.suspendEvents();
				comboPais.setValue(fieldPais.getValue());
				comboPais.resumeEvents();
			});
		}
    },
    initComponent: function ()
    {
        var me = this;
        var paisStore = Ext.create('widget.store.Pais', {
        });
        var provinciaStore = Ext.create('widget.store.Provincia', {
        });
        var poblacionStore = Ext.create('widget.store.Poblacion', {
        });
        // cargamos el árbol con las opciones
        Ext.apply(me, {
            layout: {
                align: 'stretch',
                type: 'hbox'
            },
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '96%'
            },
            items: [
            {
                xtype: 'container',
                cls: 'panelDefuncion',
                padding: 6,
                layout: {
                    type: 'anchor'
                },
                width: 190,
                items: [
                {
                    xtype: 'textfield',
                    itemId: 'campoCordenadas',
                    fieldLabel: t('Coords'),
                    name: me.cordenadasName,
                    readOnly: true,
                    cls: "x-item-disabled"
                }, {
                    xtype: 'textfield',
                    fieldLabel: t('Country'),
                    itemId: 'campoNombrePais',
                    name: me.paisName,
					hidden:true
                }, {
                    xtype: 'textfield',
                    fieldLabel: t('State/Province'),
                    itemId: 'campoNombreProvincia',
                    name: me.provinciaName,
					hidden:true
                }, {
                    xtype: 'textfield',
                    fieldLabel: t('City'),
                    itemId: 'campoNombrePoblacion',
                    name: me.poblacionName,
					hidden:true
                },{
                    xtype: 'combobox',
                    plugins: ['clearbutton'],
                    store: paisStore,
                    submitValue: false,
                    checkChangeBuffer: 100,
                    displayField: 'nombre',
                    valueField: 'nombre',
					listConfig:{
						loadMask:false
					},
                    fieldLabel: t('Country'),
                    forceSelection: true,
                    queryMode: 'local',
                    listeners: {
                        change: me.PaisChange,
                        scope: me
                    }
                }, {
                    xtype: 'combobox',
                    store: provinciaStore,
                    plugins: ['clearbutton'],
                    displayField: 'nombre',
                    valueField: 'nombre',
					listConfig:{
						loadMask:false
					},
                    fieldLabel: t('State/Province'),
                    forceSelection: false,
                    checkChangeBuffer: 100,
                    queryMode: 'local',
                    disabled: false,
                    listeners: {
                        change: me.ProvinciaChange,
                        focus: me.ProvinciaFocus,
                        scope: me
                    }
                }, {
                    xtype: 'combobox',
                    store: poblacionStore,
                    plugins: ['clearbutton'],
                    forceSelection: false,
                    queryMode: 'local',
                    fieldLabel: t('City'),
                    displayField: 'nombre',
					listConfig:{
						loadMask:false
					},
                    valueField: 'nombre',
                    checkChangeBuffer: 100,
                    disabled: false,
                    listeners: {
                        change: me.PoblacionChange,
                        focus: me.PoblacionFocus,
                        scope: me
                    }
                },
                {
                    xtype: 'textareafield',
                    name: me.direccionName,
                    fieldLabel: t('Address'),
                    itemId: 'campoDireccion'
                }, {
                    xtype: 'button',
                    text: t('Mark Address'),
                    disabled: false,
                    iconCls: 'iconoPin',
                    handler: function (button)
                    {
                        var comboPais = button.up('container').down('#campoNombrePais');;
                        var comboProvincia = button.up('container').down('#campoNombreProvincia');
                        var comboPoblacion = button.up('container').down('#campoNombrePoblacion');
                        var campoDireccion = button.up('container').down('#campoDireccion');
                        var direccion = "";
                        direccion += campoDireccion.getValue() != '' ? campoDireccion.getValue() : '';
                        if (comboPoblacion.getValue() != "")
                        {
                            if (direccion != "") direccion += ', ' + comboPoblacion.getValue();
                            else direccion += comboPoblacion.getValue();
                        }
                        if (comboProvincia.getValue() != "")
                        {
                            if (direccion != "") direccion += ', ' + comboProvincia.getValue();
                            else direccion += comboProvincia.getValue();
                        }
                        if (comboPais.getValue() != "")
                        {
                            if (direccion != "") direccion += ', ' + comboPais.getValue();
                            else direccion += comboPais.getValue();
                        }
                        var mapa = button.up('container').next('gmappanel');
                        mapa.zoomLevel = 12;
                        mapa.geoCodeLookup(direccion, {
                            title: direccion
                        }, true, true, undefined);
                        Ext.Function.defer(function ()
                        {
                            button.up('container').down('#campoCordenadas').setValue(mapa.getCenterLatLng().lat + ',' + mapa.getCenterLatLng().lng);
                        }, 500);
                    }
                }]
            }, {
                xtype: 'gmappanel',
                flex: 1,
                layout: 'anchor',
                zoomLevel: 8,
                minGeoAccuracy: 'APPROXIMATE',
                gmapType: 'map',
                anchor: '100% 100%',
                mapConfOpts: ['enableScrollWheelZoom', 'enableDoubleClickZoom', 'enableDragging'],
                mapControls: ['GSmallMapControl', 'GMapTypeControl'],
                listeners: {
                    activate: function (mapa)
                    {
                        function centrarMapa()
                        {
                            var latlng = mapa.up('container').down('#campoCordenadas').getValue();
                            if (latlng != "")
                            {
                                // para centrar el mapa
                                var cordenadas = "";
                                var latitud = 39.958175;
                                var longitud = -0.162048;
                                cordenadas = latlng.split(',');
                                latitud = parseFloat(cordenadas[0]).toFixed(20);
                                longitud = parseFloat(cordenadas[1]).toFixed(20);
                                var punto = new google.maps.LatLng(latitud, longitud);
                                mapa.addMarker(punto, {
                                    title: 'Posición'
                                }, true, true, undefined);
                            }
                        };
                        // si el mapa no está listo nos esperamos a que lo esté para centrar el punto	
                        if (!mapa.mapDefined)
                        {
                            mapa.on('mapready', centrarMapa, mapa);
                        }
                        else centrarMapa();
                    }
                },
                setCenter: {
                    lat: 39.958175,
                    lng: -0.162048
                }
            }]
        });
        // llamamos a la función initComponent de su superclase
        me.callParent(arguments);
    },
    CargarDatos: function (component)
    {
        var comboPais = component.down('combobox');
        var comboProvincia = comboPais.next('combobox');
        var comboPoblacion = comboProvincia.next('combobox');
		var fieldProvincia = component.down('#campoNombreProvincia');
        
        // cargamos los datos
        comboPais.store.load();
        comboProvincia.store.load();
        comboPoblacion.store.getProxy().extraParams.provincia = fieldProvincia.getValue();
        comboPoblacion.store.load();
    },
    PaisChange: function (combo)
    {
        var pais = combo.getValue();
        if (pais != "") combo.up('container').down('#campoNombrePais').setValue(pais);
    },
    ProvinciaChange: function (combo)
    {
        var provincia = combo.getValue();
        if (provincia != "") combo.up('container').down('#campoNombreProvincia').setValue(provincia);
    },
    PoblacionChange: function (combo)
    {
        if (!combo.store.cargado) return;
        var poblacion = combo.getValue();
        combo.up('container').down('#campoNombrePoblacion').setValue(poblacion);
    },
    ProvinciaFocus: function (combo)
    {
        var pais = combo.up('container').down('#campoNombrePais').getValue();
        if (pais == 'España')
        {
            if (combo.store.count() == 0) combo.store.load();
        }
        else combo.store.removeAll();
    },
    PoblacionFocus: function (combo)
    {
        var provincia = combo.up('container').down('#campoNombreProvincia').getValue();
        var pais = combo.up('container').down('#campoNombrePais').getValue();
        if (combo.store.getProxy().extraParams.provincia != provincia)
        {
            if (pais == 'España')
            {
                combo.store.getProxy().extraParams.provincia = provincia;
                combo.store.load();
            }
            else combo.store.removeAll();
        }
    }
});
// grid genérica con botones para agregar, editar y eliminar
Ext.define('RESCATE.grid.grid', {
    extend: 'Ext.grid.Panel',
    // referencia al formulario de edición asociado a este grid
    formulario: null,
    // mensaje del título del formulario al hacer click en el botón agregar
    mensajeAgregar: '',
    // para mostrar solo los iconos y no los textos en buscar y refrescar
    soloIconos: false,
    // para mostrar solo los iconos y no los textos en agregar editar y refrescar
    soloIconosEdicion: false,
    // si es false ocultamos los iconos de agregar,editar,eliminar
    botonAgregar: true,
    botonEditar: true,
    botonBorrar: true,
    botonMarcar: false,
	botonBuscar: true,
    idMarcar: '',
    // itemId del campo donde se guarda el valor de la relación
    idRelacion: '',
    // nombre del campo en la bd en el que se guarda la relación
    nombreRelacion: '',
    forceFit: false,
    viewConfig: {
        loadingText: t('Loading...'),
        loadMask: true
    },
    initComponent: function ()
    {
        var me = this;
		
		var ordenacionMultiple = Ext.create('Ext.ux.grid.feature.MultiSorting');
		
		//para ordenar multiples columnas
		me.features ? me.features.push(ordenacionMultiple): me.features = [ordenacionMultiple];
		
        // mi plugin de barra de herramientas con los botones para agregar, editar, borrar e.t.c
        var pluginHerramientas = new Ext.create('Ext.ux.BarraHerramientasPanel', {
            soloIconos: me.soloIconos,
            soloIconosEdicion: me.soloIconosEdicion,
            botonAgregar: me.botonAgregar,
            botonEditar: me.botonEditar,
            botonBorrar: me.botonBorrar,
            botonMarcar: me.botonMarcar,
			botonBuscar: me.botonBuscar,
            idMarcar: me.idMarcar
        });
        if (me.plugins)
        {
            me.plugins.push(pluginHerramientas);
        }
        else
        {
            me.plugins = [pluginHerramientas];
        }
        me.bbar =
        {
            xtype: 'pagingtoolbar',
            store: me.store,
            displayInfo: true,
            displayMsg: t('Displaying {0} - {1} of {2}'),
            emptyMsg: t('Nothing to display'),
            //Ocultamos el botón de refrescar porque tenemos el nuestro propio
            listeners: {
                'afterrender': function (component)
                {
                    component.down('#refresh').hide()
                },single: true
            },
            plugins: [new Ext.create('Ext.ux.PagingToolbarResizer', {
                displayText: '',
                options: [5, 10, 15, 20, 25, 50, 100]
            })]
        };
        me.getSelectionModel().on('selectionchange', me.onSelectChange, me);
		
        me.callParent(arguments);
    },
    listeners: {
        itemdblclick: function (dv, record, item, index, e)
        {
            this.onEditClick();
        },
        activate: function (component, options)
        {
			var storeGrid = component.getStore();
            if (component.idRelacion != '')
            {
                var id = component.up('form').down('#' + component.idRelacion).getValue();
                storeGrid.getProxy().extraParams.id = id;
                storeGrid.load();
            }
            else
            {
                // vamos a forzar la máscara
                if (component.getEl()) component.getEl().mask(t('Loading...'));
                storeGrid.load(storeGrid.lastOptions);
                storeGrid.on('load', function ()
                {
                    component.getEl().unmask()
                });
            }
        },
        /* Para solucionar un bug por el cual la barra horizontal del grid deja de funcionar*/
        scrollershow: function (scroller)
        {
            if (scroller && scroller.scrollEl)
            {
                scroller.clearManagedListeners();
                scroller.mon(scroller.scrollEl, 'scroll', scroller.onElScroll, scroller);
            }
        }
    },
    // al seleccionar una fila activamos los botones editar y eliminar
    onSelectChange: function (selModel, selections)
    {
        if (this.botonBorrar) this.down('#botonBorrar').setDisabled(selections.length === 0);
        if (this.botonEditar) this.down('#botonEditar').setDisabled(selections.length === 0);
    },
    selModel: {
        mode: 'SINGLE'
    }
});
Ext.define('RESCATE.TreePanel', {
    extend: 'Ext.tree.Panel',
    title: '',
    // para mostrar solo los iconos y no los textos
    soloIconos: true,
    // para mostrar solo los iconos y no los textos en agregar editar y refrescar
    soloIconosEdicion: true,
    // si es false ocultamos los iconos de agregar,editar,eliminar
    botonAgregar: true,
    botonEditar: true,
    botonBorrar: true,
	botonBuscar:false,
    // itemId del campo donde se guarda el valor de la relación
    idRelacion: '',
    nombreRelacion: '',
    //formulario de edicion
    formulario: '',
    initComponent: function ()
    {
        var me = this;
        // mi plugin de barra de herramientas con los botones para agregar, editar, borrar e.t.c
        var pluginHerramientas = new Ext.create('Ext.ux.BarraHerramientasPanel', {
            soloIconos: me.soloIconos,
            soloIconosEdicion: me.soloIconosEdicion,
            botonAgregar: me.botonAgregar,
            botonEditar: me.botonEditar,
            botonBorrar: me.botonBorrar,
			botonBuscar:me.botonBuscar
        });
        if (me.plugins)
        {
            me.plugins.push(pluginHerramientas);
        }
        else
        {
            me.plugins = [pluginHerramientas];
        }
        me.getSelectionModel().on('selectionchange', me.onSelectChange, me);
        me.callParent(arguments);
    },
    // al seleccionar una fila activamos los botones editar y eliminar
    onSelectChange: function (selModel, selections)
    {
        if (this.botonBorrar) this.down('#botonBorrar').setDisabled(selections.length === 0);
        if (this.botonEditar) this.down('#botonEditar').setDisabled(selections.length === 0);
    },
    listeners: {
        itemdblclick: function (dv, record, item, index, e)
        {
            if (record.data['id'] === 0) return false;
            this.onEditClick();
        },
        activate: function (component, options)
        {	
			var storeGrid = component.getStore();
            if (component.idRelacion != '')
            {
                var id = component.up('form').down('#' + component.idRelacion).getValue();
                storeGrid.getProxy().extraParams.id = id;
                storeGrid.getProxy().extraParams.norel = 1;
            }
            
			storeGrid.getRootNode().expand();
			//storeGrid.load();
			// 4.1 gpl si hago el load directamente da error
			Ext.Function.defer(function ()
			{
				storeGrid.load();
			}, 1000);
        },
        resize: function (component)
        {
            // cuando hay scrollbar no pilla el tamaño correcto y hay que forzarle a que recalcule la altura del panel
            component.doLayout();
        }
    }
});
Ext.define('RESCATE.toolbar.ToolbarOpciones', {
    extend: 'Ext.toolbar.Toolbar',
    itemIdContenedor: '',
    onItemClick: function (button)
    {
        var me = this;
        var containerDetalles = me.up('form').down('#' + me.itemIdContenedor);
        var idPanelHijo = button.panelId.replace(/\./g, '');
        var panelYaCreado = containerDetalles.down('#' + idPanelHijo);
        // Forzando lazy rendering, creamos los componentes en el momento en que se necesiten y los agregamos al panel. 
        // Si los destruimos y creamos cada vez, internet explorer empieza a tener fugas de memoria.
        // Así que no destruyo nada, y si un panel se vuelve a necesitar, lo activo.
        if (!panelYaCreado)
        {
            var componente = Ext.create(button.panelId, {
                'itemId': idPanelHijo,
				border:0
            });
			//Ext.suspendLayouts();
            containerDetalles.add(componente);
			//Ext.resumeLayouts();
        }
        containerDetalles.layout.setActiveItem(idPanelHijo);
    }
});
Ext.define('RESCATE.form.editar', {
    extend: 'Ext.window.Window',
    alias: 'widget.form.editar',
    // grid al que está asociado el formulario de edicion
    grid: null,
    shadow: !Ext.isIE,
    closeAction: 'hide',
    layout: 'fit',
	border:0,
    autoShow: false,
    modal: true,
    // si se agrega un registro o el update falla entonces esta variable se pondrá a true
    refrescar: false,
    listeners: {
        close: function ()
        {
            if (this.refrescar) this.RefrescarStore();
            if (this.closeAction == 'destroy') this.grid.formEdicion = null;
            Ext.getBody().unmask();
        },
        // al cerrar refrescamos el grid para descartar errores y actualizar el paginador en caso de que se hayan agregado registros
        show: function ()
        {
            Ext.getBody().unmask();
            // si hay un panel con pestañas ponemos siempre la primera como seleccionada
            var tabPanel = this.down('tabpanel');
            if (tabPanel) tabPanel.setActiveTab(0);
        }
    },
    RefrescarStore: function ()
    {
        var store = this.grid.getStore();
        store.load(store.lastOptions);
		this.refrescar = false;
    },
    CargarRecord: function (record)
    {
        var formulario = this.down('form');
		
		// si es un record nuevo ponemos su id a "" para que el sistema sepa que es un record nuevo
		if(!record.getId())record.set(record.idProperty,"");
		
        formulario.loadRecord(record);
		
        this.show();
        // la centramos por si se había movido fuera de sitio
        this.center();
        // desmarcamos los campos que estén marcados como inválidos
        var campos = formulario.getForm().getFields();
        campos.each(function (item)
        {
            if (item.isDirty)
            {
                item.clearInvalid();
            }
        });
        this.fireEvent('datoscargados', formulario);
        this.EstablecerPermisos(formulario);
    },
    // función a la que se llamará después de haber hecho un sync correcto
    SyncCallBack: function (batch, options)
    {
		var store = this.grid.getStore();
        store.formulario = null;
        this.close();
        // mostramos la confirmacion
        RESCATE.confirmacion();
    },
    GuardarCambios: function (button)
    {
        var me = this;
        var form = me.down('form');
        var record = form.getRecord();
        var values = form.getValues();
        var store = me.grid.getStore();
        me.refrescar = true;
        store.formulario = form;
        if (form.getForm().isValid())
        {
            // actualizamos el record
            record.set(values);
            // lo marcamos como modificado para obligarle a que haga un sync
            record.setDirty();
            // si es un registro nuevo hay que crearlo.
            if (record.phantom)
            {
                if (store.insert) store.insert(0, record);
            }
            // si es la hoja de un árbol la guardamos con nuestra función, sino utilizamos el sync del datastore
            if (store.guardarNodo)
            {
                store.guardarNodo(record, function ()
                {
                    me.SyncCallBack()
                });
            }
            else
            {
                // ponemos la máscara
                Ext.getBody().mask(t('Loading...'));
				
                // intentamos sincronizar, y si todo ha ido bien preguntamos al usuario si quiere continuar            
				store.sync({callback:function(batch,options){if(batch.hasException)return false; else me.SyncCallBack(batch, options)}});
            }
        }
        else
        {
            Ext.MessageBox.show(
            {
                title: t('Error'),
                msg: t('Some fields are invalid, please check them before continue'),
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.Msg.OK
            });
        }
    },
    comprobarUnico: function (field, options, parametros, ruta)
    {
        var botonGuardar = field.up('form').down('#botonGuardar');
        // para mostrar un circulo mientras carga
        field.addCls('cargandoCampo');
        // preguntamos al servidor
        Ext.Ajax.request(
        {
            url: ruta,
            method: 'POST',
            params: parametros,
            success: function (o)
            {
                if (o.responseText == 0)
                {
                    field.markInvalid(t('Already In Use'));
                    //botonGuardar.setDisabled(true);
                }
                else
                {
                    field.clearInvalid();
                }
                var quitarAnimacion = function (name)
                {
                    try
                    {
                        field.removeCls('cargandoCampo')
                    }
                    catch (err)
                    {
                    }
                };
                Ext.Function.defer(quitarAnimacion, 200);
            }
        });
        return true;
    },
    EstablecerPermisos: function (formulario)
    {
        // funcion para ser sobreescrita en caso de que el formulario deba controlar permisos
        return false;
    },
    BloqDebloqTab: function (tab, bloquear)
    {
        if (tab)
        {
            if (bloquear)
            {
				tab.tab.setIconCls('iconoBloqueado2');
                tab.disable();
                tab.tab.setTooltip(
                {
                    title: t('Option Blocked'),
                    text: t('You must save changes before')
                });
            }
            else
            {
                tab.tab.setIconCls('');
                tab.setDisabled(false);
                tab.tab.setTooltip(
                {
                    text: ''
                });
            }
        }
    },
    barraBotones: function ()
    {
        var me = this;
        var barraBotones = [
        {
            xtype: 'container',
            flex: 1,
            dock: 'bottom',
            padding: 4,
            items: [
            {
                xtype: 'button',
                scope: me,
                cls: 'botonFormulario',
                handler: me.close,
                text: t('Cancel')
            }, {
                xtype: 'button',
                cls: 'botonFormulario',
                text: t('OK'),
                itemId: 'botonGuardar',
                handler: me.GuardarCambios,
                scope: me,
                formBind: true
            }]
        }];
        return barraBotones;
    }
});
// panel con dos grids, uno de tipo RESCATE.grid.Drop, y otro normal que servirá como lista.
// Desde el normal, que se encuentra a la derecha se podrán arrastar registros al de la izquierda.
Ext.define('RESCATE.container.dragDrop', {
    extend: 'Ext.container.Container',
    alias: 'widget.container.dragDrop',
    title: '',
    border: 0,
    gridEditXtype: '',
    gridEditItemId: '',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: '',
    gridListXtype: '',
    gridListItemId: '',
    idRelacion: '',
    layout: {
        align: 'stretch',
        type: 'hbox'
    },
    listeners: {
        activate: function (component, options)
        {
            var gridEditar = component.down('#' + component.gridEditItemId);
            var gridListar = component.down('#' + component.gridListItemId);
            gridListar.getStore().getProxy().extraParams.norel = 1;
            // lanzamos el evento activate de los grids
			// si utilizara el afterrender tendría problemas porque al no destruir las ventanas, los grids mostrarían información antigua
            gridEditar.fireEvent('activate', gridEditar);
            gridListar.fireEvent('activate', gridListar);
        },
        resize: function (component)
        {
            // cuando hay scrollbar no pilla el tamaño correcto y hay que forzarle a que recalcule la altura del panel
			// en 4.1b3 sigue pasando con los treepanels
            var gridListar = component.down('#' + component.gridListItemId);
            // tengo que hacer esto para que ponga el tamaño correcto al panel, sino no recalcula la altura si el panel tenía un scrollbar
            gridListar.setHeight(component.getHeight());
        }
    },
    initComponent: function ()
    {
        var me = this;
        me.items = [
        {
            xtype: me.gridEditXtype,
            itemId: me.gridEditItemId,
            idGridLista: me.gridListItemId,
            DDGroup: me.DDGroup,
            idRelacion: me.idRelacion,
			border:0,
            flex: 3
        }, {
            xtype: me.gridListXtype,
            itemId: me.gridListItemId,
            idRelacion: me.idRelacion,
            flex: 2,
			border:0,
			cls:'gridDrag',
            viewConfig: {
                loadingText: t('Loading...'),
                plugins: {
                    ptype: 'gridviewdragdrop',
                    dragGroup: me.DDGroup,
                    enableDrop: false
                }
            }
        }];
        me.callParent(arguments);
    }
});
// grid preparado para arrastar records sobre él
Ext.define('RESCATE.grid.Drop', {
    alias: 'widget.grid.Drop',
    extend: 'RESCATE.grid.grid',
    /*Campos personalizados obligatorios*/
    // dropGroup
    DDGroup: '',
    // parámetro 1 de borrado
    deleteParam1: '',
    // parámetro2 de borrado
    deleteParam2: '',
    // itemId del campo que guarda el valor de la relación
    idRelacion: '',
    // para indicar si en la lista puede haber solo un registro
    registroUnico: false,
    // para indicar si pueden haber registros duplicados
    registrosDuplicados: false,
    // id del grid lista
    idGridLista: '',
    // itemId del campo donde se guardará la id del registro que se relacionará al arrastrar y soltar. 
    // P.ej, al arrastar un elemento de la lista de bibliografías a la lista de bibliografías del restaurador, será "campoIdProfesional"
    // porque es en ese campo donde guardo la id del Restaurador. Al guardar los cambios necesito tanto la id de la bibliografía como la del restaurador para poder crear la relación				   
    // propiedades adicionales de nuestro grid personalizado
    initComponent: function ()
    {
        var me = this;
        // sobreescribimos la función de borrado, porque aquí necesitamos pasarle dos parámetros
        me.onDeleteClick = function (button)
        {
            var selection = me.getView().getSelectionModel().getSelection()[0];
            var store = button.up('panel').getStore();
            var errores = true;
            if (selection)
            {
                Ext.Msg.show(
                {
                    title: t('Confirmation'),
                    msg: t('Are you sure you want to delete?'),
                    buttons: Ext.Msg.YESNO,
                    closable: false,
                    fn: function (btn)
                    {
                        if (btn == 'yes')
                        {
                            var params =
                            {
                            };
                            if (me.deleteParam1) params[me.deleteParam1] = selection.data[me.deleteParam1];
                            if (me.deleteParam2) params[me.deleteParam2] = selection.data[me.deleteParam2];
                            store.eliminar(selection, params);
                            // si encontramos el grid lista, lo refrescamos
                            var gridLista = Ext.ComponentQuery.query('#' + me.idGridLista)[0];
                            if (gridLista != 'undefined' && gridLista != null)
                            {
                                // tengo que hacerlo con un retardo porque sino el estúpido iexplorer no lo hace
                                Ext.Function.defer(function ()
                                {
                                    storeLista = gridLista.getStore();
                                    storeLista.load(storeLista.lastOptions);
                                }, 500, me);
                            }
                        }
                    }
                });
            }
        };
        me.viewConfig =
        {
            //loadMask: false,
            loadingText: t('Loading...'),
            // en formularios complejos se produce un error si esta opción está activada. En la versión 4.1 deberían haberlo resuelto
            plugins: {
                ptype: 'gridviewdragdrop',
                pluginId: 'ddplugin',
                dropGroup: me.DDGroup
            },
            listeners: {
                beforedrop: function (node, data, model, pos, drop)
                {						
					me.beforeDrop(node, data, model, pos, drop, this);
					return false;
                },
                drop: function (node, data, dropRec, dropPosition)
                {
					this.store.sync();
                }
            }
        };
        this.callParent(arguments);
    },
	beforeDrop: function (node, data, model, pos, drop, view)
	{						
		var me = this;
		// evitamos que arrastre objetos con la propiedad allowDrag = false
		if (data.records[0].data.allowDrag === false) return false;
		// si solo se permite un registro
		if (me.getStore().count() > 0 && me.registroUnico)
		{
			Ext.MessageBox.alert(
			{
				title: t('Warning'),
				msg: t('This list can contain only one value'),
				buttons: Ext.MessageBox.OK
			});
			return false;
		}
		
		
		// evitamos que arrastre registros duplicados
		if (me.getStore().getById(data.records[0].internalId) && !me.registrosDuplicados){
			Ext.MessageBox.alert(
			{
				title: t('Warning'),
				msg: t('Duplicated value not allowed'),
				buttons: Ext.MessageBox.OK
			});
			return false;
		}
		//if(me.getStore().getById( ))
		Ext.MessageBox.confirm(t('Confirmation'), t('Do you want to add the relation?'), function (btn)
		{
			if (btn == 'yes')
			{
				var plugin = this.getPlugin('ddplugin'),
					dropZone = plugin.dropZone;
				// reset this since these props were deleted by the 'return false'
				dropZone.overRecord = model;
				dropZone.currentPosition = pos;					
				
				// si acepto records repetidos tengo que clonarlos y generarles una id única para no tener problemas
				if (me.registrosDuplicados)
				{
					var temp = data.records[0].copy();
					Ext.data.Model.id(temp);
					data.records[0] = temp;
				}
				
				// lo marcamos como modificado para que al hacer sync guarde los cambios en la bd
				data.records[0].setDirty();
				
				// en extjs 4.1 hay que llamar a drop.processDrop() y no a drop() así que hacemos la comprobación
				(typeof drop.processDrop === 'function') ? drop.processDrop() : drop();
			}
		}, view);
		// go ahead and return false for now to stop the drop
		// since we will handle the drop later ourselves
		return false;
	}
});

Ext.define('RESCATE.window.subirArchivo', {
    extend: 'Ext.window.Window',
    closeAction: 'hide',
    alias: 'widget.window.subirArchivo',
    resizable: false,
    modal: true,
    title: t('Upload'),
	url:'',
	desc:CONFIG.ImagenesPermitidas,
    storeBusqueda: '',
    initComponent: function ()
    {
        var me = this;
        me.items = [
        {
            xtype: 'form',
            bodyPadding: 10,
            items: [
            {
                xtype: 'filefield',
                hideLabel: true,
                id: 'form-file',
                name: 'photo-path',
                value: '',
                buttonText: 'Buscar archivo...',
                anchor: '100%',
                allowBlank: false
            }, {
                xtype: 'container',
                html: 'La operación puede tardar varios minutos.<br>' + 'Archivos permitidos:'+me.desc+'.<br>' + 'Tamaño máximo permitido 10MB'
            }, {
                xtype: 'textfield',
                name: 'id',
                hidden: true,
                allowBlank: false
            }],
            dockedItems: [
            {
                xtype: 'container',
                dock: 'bottom',
                padding: 4,
                items: [
                {
                    xtype: 'button',
                    cls: 'botonFormulario',
                    text: 'Cancelar',
                    handler: me.close,
                    scope: me
                }, {
                    xtype: 'button',
                    cls: 'botonFormulario',
                    text: 'Aceptar',
                    formBind: true,
                    handler: me.onUploadClick,
                    scope: me
                }]
            }]
        }];
        me.keys = [
        {
            key: [Ext.EventObject.ENTER],
            handler: function ()
            {
                me.onSearchClick
            }
        }];
        me.callParent(arguments);
    },
    // al hacer doble click sobre el botón guardar
    onUploadClick: function (button)
    {
        var form = this.down('form').getForm();
        var me = this;
        if (form.isValid())
        {
            form.submit(
            {
                /*url: 'datos/documento.php?action=upload',*/
				url: me.url,
                waitMsg: 'Espere mientras se envía el archivo...',
                success: function (fp, o)
                {
                    me.vistaPrevia.setSrc(o.result.data['miniatura']);
                    me.close();
                    RESCATE.confirmacion();
                },
                failure: function (form, action)
                {
                    Ext.Msg.alert('¡Atención!', 'Error: ' + action.response.responseText);
                    me.down('[name=photo-path]').reset();
                }
            });
        }
    }
});

// datefield preconfigurado
Ext.define('RESCATE.form.Datefield', {
    alias: 'widget.form.DateField',
    extend: 'Ext.form.DateField',
    plugins: ['clearbutton'],
    format: CONFIG.dateFormat,
    editable: false,
    enableKeyEvents: true,
    listeners: {
        keydown: function (combo, e)
        {
            e.stopEvent();
        }
    }
});

Ext.define('RESCATE.form.ComboGrid', {
    extend: 'Ext.form.ComboBox',
    alias: 'RESCATE.form.ComboGrid',
	gridName:'Ext.grid.Panel',
    // copied from ComboBox 
    createPicker: function() {
        var me = this,
        picker,
        menuCls = Ext.baseCSSPrefix + 'menu',
        opts = Ext.apply({
            selModel: {
                mode: me.multiSelect ? 'SIMPLE' : 'SINGLE'
            },
            floating: true,
            hidden: true,
            ownerCt: me.ownerCt,
            cls: me.el.up('.' + menuCls) ? menuCls : '',
            store: me.store,
            displayField: me.displayField,
            focusOnToFront: false,
            pageSize: me.pageSize
        }, me.listConfig, me.defaultListConfig);


		// NOTE: we simply use a grid panel
			//picker = me.picker = Ext.create('Ext.view.BoundList', opts);


		//picker = me.picker = Ext.create('Ext.grid.Panel', opts);
		picker = me.picker = Ext.create(me.gridName, opts);


		// hack: pass getNode() to the view
		picker.getNode = function() {
			picker.getView().getNode(arguments);
		};

		me.mon(picker.getView(), {
			refresh: me.onListRefresh,
			scope:me
		});
        me.mon(picker, {
            itemclick: me.onItemClick,
//            refresh: me.onListRefresh,
            scope: me
        });


        me.mon(picker.getSelectionModel(), {
            selectionChange: me.onListSelectionChange,
            scope: me
        });


        return picker;
    }
});

// combobox preconfigurado
Ext.define('RESCATE.form.ComboBox', {
    alias: 'widget.form.ComboBox',
    extend: 'Ext.form.ComboBox',
    triggerAction: 'all',
    autoScroll: true,
    editable: false,
    listConfig: {
        loadMask: false
    },
    // para que no vuelva a perdirle al servidor la lista una vez cargada
    queryMode: 'local',
    enableKeyEvents: true,

	
    setValue: function(value, doSelect) {
        if(this.store.loading){
            this.store.on('load', Ext.bind(this.setValue, this, arguments));
            return;
        }
        this.callParent(arguments);
    },

	
    listeners: {
        keydown: function (combo, e)
        {
            e.stopEvent();
        }/*,
        afterrender: function (combo, options)
        {
            // cuando el store se haya cargado le ponemos el valor
            combo.store.on('load', function (store)
            {
                combo.setValue(combo.getValue());
                // para asegurarnos de que no lo marque como inválido si hay un valor seleccionado
                combo.validate();
            });
        },single: true*/
    }
});
/*-------------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------ VTYPES -------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------*/
Ext.apply(Ext.form.field.VTypes, {
    daterange: function (val, field)
    {
        var date = field.parseDate(val);
        if (!date)
        {
            return false;
        }
        if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime())))
        {
            var start = field.up('form').down('#' + field.startDateField);
            start.setMaxValue(date);
            start.validate();
            this.dateRangeMax = date;
        }
        else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime())))
        {
            var end = field.up('form').down('#' + field.endDateField);
            end.setMinValue(date);
            end.validate();
            this.dateRangeMin = date;
        }
/*
         * Always return true since we're only using this vtype to set the
         * min/max allowed values (these are tested for after the vtype test)
         */
        return true;
    },
    daterangeText: t('Start date must be less than end date'),
    password: function (val, field)
    {
        if (field.initialPassField)
        {
            var pwd = field.up('form').down('#' + field.initialPassField);
            return (val == pwd.getValue());
        }
        return true;
    },
    passwordText: t('Passwords do not match')
});
/*-------------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------ FUNCIONES COMUNES --------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------*/
// para mostrar mensajes de confirmación
RESCATE.confirmacion = function ()
{
    // desbloqueamos la interfaz
    Ext.getBody().unmask();
    Ext.MessageBox.show(
    {
        title: t('Operation successfull'),
        msg: t('Changes saved successfully'),
        icon: 'iconoOK',
        closable: false
    });
    // cerramos automáticamente el mensaje de confirmación tras 1 seg			
    Ext.Function.defer(Ext.MessageBox.hide, 1000, Ext.MessageBox);
};
// para controlar que la tecla de borrado no realize la acción "atrás" del navegador
var map = new Ext.KeyMap(document, [
{
    key: Ext.EventObject.BACKSPACE,
    fn: function (key, e)
    {
        var t = e.target.tagName;
        if (t !== "INPUT" && t !== "TEXTAREA")
        {
            e.stopEvent();
        }
    }
}]);
RESCATE.version = function ()
{
    return ('Revisión: ' + CONFIG.rev);
};
RESCATE.quitarMascara = function ()
{
    var loadingMask = Ext.get('loading-mask');
    var loading = Ext.get('loading');
    if (loading && loadingMask)
    {
        loadingMask.fadeOut(
        {
            opacity: 0,
            //can be any value between 0 and 1 (e.g. .5)
            easing: 'easeOut',
            duration: 400,
            remove: true,
            useDisplay: true,
            callback: function ()
            {
                loading.fadeOut(
                {
                    duration: 400,
                    remove: true
                })
            }
        });
    }
};
// Función para traducir strings
function t(string)
{
    return LOCALE[string] ? LOCALE[string] : string;
};