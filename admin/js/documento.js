// para no repetirlo cienmil veces lo pongo aquí
RESCATE.DocumentoGridColumnas = [
{
    text: t('Thumb.'),
    dataIndex: 'documento_miniatura',
    xtype: 'templatecolumn',
    width: 40,
    tpl: new Ext.XTemplate('<img data-qtip="' + Ext.htmlEncode('<img style="height:128px;width:128px;clear:both" src="') + '{documento_miniatura}' + Ext.htmlEncode('" />') + '" src="{documento_miniatura}" style="padding-right:4px;width:32px;height:32px" align="left">')
}, {
    text: t('Title'),
    flex: 1,
    dataIndex: 'documento_titulo',
    sortable: true
}, {
    text: t('Author Name'),
    dataIndex: 'profesional_nombre',
    sortable: true,
    hidden: true
}, {
    text: t('Surname 1'),
    dataIndex: 'profesional_apellido1',
    sortable: true,
    hidden: true
}, {
    text: t('Surname 2'),
    dataIndex: 'profesional_apellido2',
    sortable: true,
    hidden: true
}, {
    text: t('File'),
    dataIndex: 'documento_archivo',
    sortable: true,
    hidden: true
}, {
    text: t('Start Date'),
    dataIndex: 'documento_fechainicial',
    sortable: true,
    hidden: true
}, {
    text: t('End Date'),
    dataIndex: 'documento_fechafinal',
    sortable: true,
    hidden: true
}, {
    text: t('Reference'),
    dataIndex: 'documento_ref',
    sortable: true,
    hidden: true
}, {
    text: t('Country'),
    dataIndex: 'documento_pais',
    sortable: true,
    hidden: true
}, {
    text: t('State/Province'),
    dataIndex: 'documento_provincia',
    sortable: true,
    hidden: true
}, {
    text: t('City'),
    dataIndex: 'documento_poblacion',
    sortable: true,
    hidden: true
}];
Ext.define('RESCATE.form.editarDocumento', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarDocumento',
    width: 780,
    height: 540,
    maximizable: true,
    title: t('Documents'),
    initComponent: function ()
    {
        var me = this;
        var paisStore = Ext.create('widget.store.Pais', {
        });
        var provinciaStore = Ext.create('widget.store.Provincia', {
        });
        var creadorStore = Ext.create('widget.store.Creador', {
        });
		var anyoStore = Ext.create('widget.store.Anyo', {});
		
        me.items = [
        {
            xtype: 'form',
            layout: {
                type: 'fit'
            },
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '96%'
            },
            bodyPadding: 10,
            items: [
            {
                xtype: 'tabpanel',
                activeTab: 0,
                plain: true,
                items: [
                {
                    xtype: 'panel',
                    title: 'General',
                    layout: {
                        align: 'stretch',
                        padding: 6,
                        type: 'vbox'
                    },
                    items: [
                    {
                        xtype: 'container',
                        layout: {
                            type: 'column'
                        },
                        minHeight: 200,
                        items: [
                        {
                            xtype: 'fieldset',
                            padding: 4,
                            width: 138,
                            margin: 4,
                            title: 'Archivo',
                            items: [
                            {
                                xtype: 'image',
                                itemId: 'vistaPrevia',
                                width: 128,
                                height: 128
                            }, {
                                xtype: 'button',
                                itemId: 'botonAdjuntar',
                                text: 'Adjuntar...',
                                tooltip: t('Attach File'),
                                width: 76,
                                scope: me,
                                handler: me.onAttachClick
                            }, {
                                margin: '0 0 0 4',
                                xtype: 'button',
                                itemId: 'botonWebcam',
                                handler: me.onWebcamClick,
                                tooltip: t('Capture image from Webcam'),
                                scope: me,
                                iconCls: 'iconoWebcam'
                            }, {
                                margin: '0 0 0 4',
                                xtype: 'button',
                                itemId: 'botonEliminar',
                                handler: me.onDeleteFileClick,
                                tooltip: t('Delete'),
                                scope: me,
                                iconCls: 'iconoBorrar'
                            }]
                        }, {
                            xtype: 'container',
                            layout: {
                                type: 'anchor'
                            },
                            columnWidth: 0.5,
                            items: [
                            {
                                xtype: 'hiddenfield',
                                name: 'documento_id',
                                itemId: 'campoIdDocumento'
                            }, {
                                xtype: 'hiddenfield',
                                name: 'documento_archivo'
                            }, {
                                xtype: 'hiddenfield',
                                name: 'documento_miniatura'
                            }, {
                                xtype: 'textfield',
                                name: 'documento_titulo',
                                fieldLabel: t('Title') + '*',
                                allowBlank: false
                            }, {
                                xtype: 'textfield',
                                name: 'documento_ref',
                                fieldLabel: 'Nº Referencia' + '*',
                                allowBlank: false,
								listeners: {
									scope: me,
									blur: function (field, options)
									{
										me.comprobarNombreUnico(field, options)
									} /*Usamos onblur en lugar de un vtype para comprobar solo cuando pierda el foco*/
								}
                            }]
                        }, {
                            xtype: 'container',
                            layout: {
                                type: 'anchor'
                            },
                            columnWidth: 0.5,
                            items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Url',
                                anchor: '100%',
                                name: 'documento_enlace'
                            }, {
								xtype: 'form.ComboBox',
								displayField: 'anyo',
								valueField: 'anyo',
								name: 'documento_fechainicial',
								store: anyoStore,
								anchor:'100%',
								fieldLabel: t('Date (Year)')
							}]
                        }]
                    }, {
                        xtype: CONFIG.textArea,
                        flex: 1,
                        name: 'documento_descripcion',
                        fieldLabel: t('Details'),
                        anchor: '100% -5'
                    }]
                }, {
                    xtype: 'panel.ProfesionalDocumento',
                    title: t('Artist'),
                    itemId: 'panelAutor'
                }, {
                    xtype: 'panel.Tipo',
                    title: t('Type'),
                    itemId: 'panelTipos'
                }, {
                    xtype: 'panelDireccion',
                    paisName: 'documento_pais',
                    provinciaName: 'documento_provincia',
                    poblacionName: 'documento_poblacion',
                    direccionName: 'documento_direccion',
                    cordenadasName: 'documento_cordenadas',
                    title: t('Location'),
                    paisStore: paisStore,
                    provinciaStore: provinciaStore
                }, {
                    xtype: 'panel',
                    padding: 6,
                    itemId: 'panelAdministracion',
                    title: t('Administration'),
                    items: [
                    {
                        xtype: 'checkboxfield',
                        name: 'documento_supervisado',
                        fieldLabel: t('Checked'),
                        boxLabel: t('Yes'),
                        uncheckedValue: 0
                    }, {
                        xtype: 'checkboxfield',
                        name: 'documento_bloqueado',
                        fieldLabel: t('Blocked'),
                        boxLabel: t('Yes'),
                        uncheckedValue: 0
                    }, {
                        xtype: 'form.ComboBox',
                        displayField: 'nombre',
                        valueField: 'id',
                        name: 'documento_usuario_id',
                        store: creadorStore,
                        fieldLabel: t('Creator'),
                        emptyText: t('Me'),
                        width: 400
                    }, {
                        xtype: 'textfield',
                        name: 'documento_ultimamod',
                        readOnly: true,
                        submitValue: false,
                        fieldLabel: t('Last Modification'),
                        width: 200
                    }]
                }]
            }],
            dockedItems: me.barraBotones()
        }];
        me.addListener('datoscargados', function (component)
        {
            me.down('#vistaPrevia').setSrc(me.down('[name=documento_miniatura]').getValue());
            paisStore.load();
            provinciaStore.load();
            creadorStore.load();
			anyoStore.load();
        });
        me.callParent(arguments);
    },
    EstablecerPermisos: function ()
    {
		var me = this;
		// Si no es administrador desactivamos el panel de administracion
        var tabAdministracion = me.down('#panelAdministracion');
        if (!CONFIG.perms.administrar_registros)
        {
            this.BloqDebloqTab(tabAdministracion, true);
            tabAdministracion.tab.setTooltip(
            {
                title: t('Option Blocked'),
                text: t('You dont have the required permissions')
            });
        }
        var documentoId = me.down('[name=documento_id]').getValue();
        var botonAdjuntar = me.down('#botonAdjuntar');
		var botonEliminar = me.down('#botonEliminar');
        var botonWebcam = me.down('#botonWebcam');
        var tabTipos = me.down('#panelTipos');
		var tabAutor = me.down('#panelAutor');
        var bloq = (documentoId == '') ? true : false;
        this.BloqDebloqTab(tabTipos, bloq);
		this.BloqDebloqTab(tabAutor, bloq);
		
        if (documentoId == '')
        {
            botonAdjuntar.setDisabled(true);
            botonWebcam.setDisabled(true);
			botonEliminar.setDisabled(true);
        }
        else
        {
            botonAdjuntar.setDisabled(false);
            botonWebcam.setDisabled(false);
			botonEliminar.setDisabled(false);
        }
    },
    comprobarNombreUnico: function (field, options)
    {
        var ruta = 'datos/documento.php';
        var idUsuario = field.up('form').down('hiddenfield[name=documento_id]').getValue();
        var parametros =
        {
            'action': 'checkUnique',
            'ref': field.getValue(),
            'idDocumento': idUsuario
        };
        this.comprobarUnico(field, options, parametros, ruta);
    },
    onWebcamClick: function ()
    {
        var documentoId = this.down('[name=documento_id]').getValue();
        if (documentoId == '') return;
        var vistaPrevia = this.down('#vistaPrevia');
        var wp = Ext.create("RESCATE.panel.Webcam");
        wp.documentoId = documentoId;
        var j = Ext.create("Ext.window.Window", {
            width: 640,
            resizable: false,
            title: t('Take Webcam Photo'),
            items: [
            wp]
        });
        wp.on("uploadComplete", function (response)
        {
            if (response.success)
            {
                RESCATE.confirmacion();
                vistaPrevia.setSrc(response.data['miniatura']);
            }
            else
            {
                Ext.MessageBox.show(
                {
                    title: response.message,
                    msg: response.errors,
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.Msg.OK
                });
            }
            j.close();
        });
        j.show();
    },
    // al hacer click sobre el botón adjuntar
    onAttachClick: function (button, toggle)
    {
        var documentoId = this.down('[name=documento_id]').getValue();
        if (documentoId == '') return;
        if (!this.subirArchivo)
        {
            this.subirArchivo = Ext.widget('window.subirArchivo',{url:'datos/documento.php?action=upload', desc:CONFIG.ImagenesPermitidas+","+CONFIG.DocumentosPermitidos+","+CONFIG.SonidosPermitidos+","+CONFIG.VideosPermitidos});
        }
        this.subirArchivo.down('[name=id]').setValue(documentoId);
        this.subirArchivo.vistaPrevia = this.down('#vistaPrevia');
        this.subirArchivo.show();
    },
	onDeleteFileClick: function (button)
	{
		var me = this;
		var documentoId = me.down('[name=documento_id]').getValue();
        if (documentoId == '') return;
		
		Ext.MessageBox.confirm(t('Confirmation'), t('Do you want to delete the file?'), function (btn)
        {
            if (btn == 'yes')
            {          
				Ext.Ajax.request(
				{
					url: 'datos/documento.php?action=deleteFile',
					method: 'POST',
					params: {id: documentoId},
					success: function (o)
					{
						if (o.responseText == 1)
						{
							RESCATE.confirmacion();
							me.down('#vistaPrevia').setSrc('');
						}
						else
						{
							Ext.MessageBox.alert(
							{
								title: t('Warning'),
								msg: t('Error Deleting File'),
								buttons: Ext.MessageBox.OK
							});
							return false;
						}
					}
				});
				return true;			
            }
        });	
	},
    //override del metodo que se ejecuta después de sincronizar
    SyncCallBack: function ()
    {
        var me = this;
		Ext.getBody().unmask();
        var form = me.down('form');
        var record = form.getRecord();
        var store = me.grid.getStore();
        store.proxy.ventana = null;
        Ext.MessageBox.confirm(t('Confirmation'), t('Success saving <br />Do you want to continue editing?'), function (btn)
        {
            if (btn == 'no')
            {
                me.close();
            }
            if (btn == 'yes')
            {
                var campoIdProfesional = form.down('[name=documento_id]');
                // si era un registro nuevo actualizamos la id del profesional en el formulario para poder activar las tabs
                if (campoIdProfesional.getValue() == '')
                {
                    // esto debo hacerlo para que al volver a darle a aceptar, actualice el record que habíamos insertado previamente
                    // sino considera que no hay nada que actualizar y no hace el sync
                    form.loadRecord(store.getAt(0));
                }
                me.EstablecerPermisos();
            }
        });
    }
});

// grid con la lista de documentos
Ext.define('RESCATE.panel.Documento', {
    extend: 'RESCATE.grid.grid',
    formulario: 'editarDocumento',
    mensajeAgregar: t('Add Document'),
    title: t('Documents List'),
    botonMarcar: true,
    stateful: true,
    stateId: 'statePanelDocumento',
    idMarcar: 'documento_supervisado',
    // propiedades adicionales de nuestro grid personalizado
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Documento', {
        });
        this.columns = [
        {
            text: t('Id'),
            width: 50,
            dataIndex: 'documento_id',
            sortable: true,
            hidden: true
        }, {
            header: "Vista Previa",
            dataIndex: 'documento_miniatura',
            flex: 1,
            searchable: false,
            xtype: 'templatecolumn',
            tpl: new Ext.XTemplate('<img data-qtip="' + Ext.htmlEncode('<img style="height:128px;width:128px;clear:both" src="') + '{documento_miniatura}' + Ext.htmlEncode('" />') + '" src="{documento_miniatura}" style="padding-right:4px;width:64px;height:64px" align="left">', '<b style="font-size:13px;">{documento_titulo}</b><br />', '<tpl if="documento_descripcion">', 'Descripción:<i> {documento_descripcion}</i><br />', '</tpl>', '<tpl if="documento_archivo">', '<div class="iconoDescargar"></div><a target="blank" href="{documento_archivo}">Descargar</a><br />', '</tpl>', '<tpl if="documento_enlace">', '<a target="blank" class="iconoDescargar" href="{documento_enlace}">Enlace</a>', '</tpl>')
        }, {
			text: t('Title'),
			flex: 1,
			dataIndex: 'documento_titulo',
			sortable: true
		}, {
			text: t('Author'),
			dataIndex: 'profesional_nombre',
			sortable: true
		}, {
			text: t('Surname 1'),
			dataIndex: 'profesional_apellido1',
			sortable: true
		}, {
			text: t('Surname 2'),
			dataIndex: 'profesional_apellido2',
			sortable: true,
			hidden: true
		}, {
            text: t('Login'),
            flex: 1,
            dataIndex: 'usuario_login',
            sortable: true
        }, {
            text: t('Creator Name'),
            flex: 1,
            dataIndex: 'usuario_nombre',
            sortable: true,
            hidden: true
        }, {
            text: t('Creator Surname 1'),
            flex: 1,
            dataIndex: 'usuario_apellido1',
            sortable: true,
            hidden: true
        }, {
            text: t('Creator Surname 2'),
            flex: 1,
            dataIndex: 'usuario_apellido2',
            sortable: true,
            hidden: true
        }, {
            text: t('Checked (0/1)'),
            width: 110,
            dataIndex: 'documento_supervisado',
            sortable: true,
            renderer: function (val)
            {
                if (val == 1) return '<div class="iconoSupervisado"></div>';
                else
                return '<div class="iconoNoSupervisado"></div>'
            }
        }, {
            text: t('Blocked (0/1)'),
            width: 110,
            dataIndex: 'documento_bloqueado',
            sortable: true,
            renderer: function (val)
            {
                if (val == 1) return '<div class="iconoBloqueado"></div>';
                else
                return '<div class="iconoNoBloqueado"></div>'
            }
        }, {
            text: t('Start Date'),
            dataIndex: 'documento_fechainicial',
            sortable: true,
            hidden: true
        }, {
            text: t('End Date'),
            dataIndex: 'documento_fechafinal',
            sortable: true,
            hidden: true
        }, {
            text: t('Reference'),
            dataIndex: 'documento_ref',
            sortable: true,
            hidden: true
        }, {
            text: t('Country'),
            dataIndex: 'documento_pais',
            sortable: true,
            hidden: true
        }, {
            text: t('State/Province'),
            dataIndex: 'documento_provincia',
            sortable: true,
            hidden: true
        }, {
            text: t('City'),
            dataIndex: 'documento_poblacion',
            sortable: true,
            hidden: true
        }];
        this.callParent(arguments);
    },
    // al seleccionar una fila activamos los botones editar y eliminar siempre que el registro no esté bloqueado
    onSelectChange: function (selModel, selections)
    {
        if (selections.length != 0)
        {
            // si es administrador o el registro no está bloqueado
            if (CONFIG.perms.administrar_registros || (selections[0].data['documento_bloqueado'] != "1" && CONFIG.perms.agregareditar_registros_propios))
            {
                if (this.botonBorrar) this.down('#botonBorrar').setDisabled(selections.length === 0);
                if (this.botonEditar) this.down('#botonEditar').setDisabled(selections.length === 0);
            }
            else
            {
                if (this.botonBorrar) this.down('#botonBorrar').setDisabled(true);
                if (this.botonEditar) this.down('#botonEditar').setDisabled(true);
            }
        }
    },// al hacer doble click sobre una fila o hacer click sobre el botón editar. Override para controlar permisos
    onEditClick: function (button)
    {
        var record = this.getView().getSelectionModel().getSelection()[0];
        if (record)
        {
            if (CONFIG.perms.administrar_registros || (record.data['documento_bloqueado'] != "1" && CONFIG.perms.agregareditar_registros_propios))
            {
                // si está en modo lista no hacemos nada
                if (!this.botonEditar) return false;
                var record = this.getView().getSelectionModel().getSelection()[0];
                if (record) this.abrirFormularioEdicion(record);
            }else{
				Ext.MessageBox.alert(
				{
					title: t('Warning'),
					msg: t('You dont have the required permissions'),
					buttons: Ext.MessageBox.OK,
					icon: Ext.MessageBox.ERROR
				});		
			}
        }
        return false;
    }
});

// grid con la lista editable de profesionales relacionados con la obra
Ext.define('RESCATE.grid.ProfesionalDocumento', {
    alias: 'widget.grid.ProfesionalDocumento',
    extend: 'RESCATE.grid.DropPro',
    title: t('Author'),
    soloIconos: true,
    botonAgregar: false,
    formulario: 'editarProfesionalDocumento',
    mensajeAgregar: t('Add Author'),
	nombreRelacion: 'documento_id',
	deleteParam1: 'profesionaldocumento_documento_id',
	deleteParam2: 'profesionaldocumento_profesional_id',
	claveAjena1: 'profesionaldocumento_documento_id',
	claveAjena2: 'profesionaldocumento_profesional_id',
    registrosDuplicados: false,
	registroUnico: true,
    columns: [
    {
        text: t('Name'),
        flex: 1,
        dataIndex: 'profesional_nombre',
        sortable: true
    }, {
        text: t('Surname 1'),
        width: 160,
        dataIndex: 'profesional_apellido1',
        sortable: true
    }, {
        text: t('Surname 2'),
        width: 160,
        dataIndex: 'profesional_apellido2',
        sortable: true,
        hidden: true
    }, {
        text: t('Details'),
        width: 100,
        dataIndex: 'profesionaldocumento_detalles',
        sortable: true,
        hidden: false
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.ProfesionalDocumento', {
        });
        this.callParent(arguments);
    }
});

//formulario editar ProfesionalDocumento
Ext.define('RESCATE.form.editarProfesionalDocumento', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarProfesionalDocumento',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Professional-Work Of Art'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        me.items = [
        {
            xtype: 'form',
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '100%'
            },
            bodyPadding: 10,
            title: '',
            items: [
            {
                xtype: 'textfield',
                name: 'profesionaldocumento_documento_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'profesionaldocumento_profesional_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'profesionaldocumento_detalles',
                margin: '0'
            }],
            dockedItems: me.barraBotones()
        }];
		me.callParent(arguments);
    }
});
// panel que une el grid de listado de profesionales y el grid editable de profesionales
Ext.define('RESCATE.panel.ProfesionalDocumento', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.ProfesionalDocumento',
    title: '',
    idRelacion: 'campoIdDocumento',
    gridEditXtype: 'grid.ProfesionalDocumento',
    gridEditItemId: 'gridProfesionalDocumento',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'ProfesionalDocumentoGridDDGroup',
    gridListXtype: 'grid.ProfesionalLista',
    gridListItemId: 'gridProfesionalLista'
});




// grid con la lista de documentos
Ext.define('RESCATE.grid.DocumentoProfesionalLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.grid.DocumentoProfesionalLista',
    title: t('List'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: false,
    botonBorrar: false,
    columns: RESCATE.DocumentoGridColumnas,
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.DocumentoProfesional', {
        });
        this.callParent(arguments);
    }
});
// grid con la lista editable de documentos
Ext.define('RESCATE.grid.DocumentoProfesional', {
    alias: 'widget.grid.DocumentoProfesional',
    extend: 'RESCATE.grid.Drop',
    title: t('Associated Documents'),
    soloIconos: true,
    botonAgregar: false,
    formulario: 'editarDocumentoProfesional',
    mensajeAgregar: t('Add Club'),
    deleteParam1: 'documentoprofesional_documento_id',
    deleteParam2: 'documentoprofesional_profesional_id',
    nombreRelacion: 'documentoprofesional_documento_id',
    columns: RESCATE.DocumentoGridColumnas,
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.DocumentoProfesional', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de documentos
Ext.define('RESCATE.panel.DocumentoProfesional', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.DocumentoProfesional',
    title:t( 'Documents'),
    idRelacion: 'campoIdProfesional',
    gridEditXtype: 'grid.DocumentoProfesional',
    gridEditItemId: 'gridDocumentoProfesional',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'DocumentoProfesionalGridDDGroup',
    gridListXtype: 'grid.DocumentoProfesionalLista',
    gridListItemId: 'gridDocumentoProfesionalLista'
});
//formulario editar documentoprofesional
Ext.define('RESCATE.form.editarDocumentoProfesional', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarDocumentoProfesional',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Professional-Document'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        me.items = [
        {
            xtype: 'form',
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '100%'
            },
            bodyPadding: 10,
            items: [
            {
                xtype: 'textfield',
                name: 'documentoprofesional_documento_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'documentoprofesional_profesional_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'documentoprofesional_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// grid con la lista de documentos
Ext.define('RESCATE.grid.DocumentoIntervencionLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.grid.DocumentoIntervencionLista',
    title: t('List'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: false,
    botonBorrar: false,
    columns: RESCATE.DocumentoGridColumnas,
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.DocumentoIntervencion', {
        });
        this.callParent(arguments);
    }
});
// grid con la lista editable de documentos
Ext.define('RESCATE.grid.DocumentoIntervencion', {
    alias: 'widget.grid.DocumentoIntervencion',
    extend: 'RESCATE.grid.Drop',
    title: t('Associated Documents'),
    soloIconos: true,
    botonAgregar: false,
    formulario: 'editarDocumentoIntervencion',
    mensajeAgregar: t('Add Club'),
    deleteParam1: 'documentointervencion_documento_id',
    deleteParam2: 'documentointervencion_intervencion_id',
    nombreRelacion: 'documentointervencion_documento_id',
    columns: RESCATE.DocumentoGridColumnas,
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.DocumentoIntervencion', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de documentos
Ext.define('RESCATE.panel.DocumentoIntervencion', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.DocumentoIntervencion',
    title:t( 'Documents'),
    idRelacion: 'campoIdIntervencion',
    gridEditXtype: 'grid.DocumentoIntervencion',
    gridEditItemId: 'gridDocumentoIntervencion',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'DocumentoIntervencionGridDDGroup',
    gridListXtype: 'grid.DocumentoIntervencionLista',
    gridListItemId: 'gridDocumentoIntervencionLista'
});
//formulario editar documentointervencion
Ext.define('RESCATE.form.editarDocumentoIntervencion', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarDocumentoIntervencion',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Document-Intervention'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        me.items = [
        {
            xtype: 'form',
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '100%'
            },
            bodyPadding: 10,
            items: [
            {
                xtype: 'textfield',
                name: 'documentointervencion_documento_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'documentointervencion_intervencion_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'documentointervencion_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// grid con la lista de documentos
Ext.define('RESCATE.grid.DocumentoObraLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.grid.DocumentoObraLista',
    title: t('List'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: false,
    botonBorrar: false,
    columns: RESCATE.DocumentoGridColumnas,
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.DocumentoObra', {
        });
        this.callParent(arguments);
    }
});
// grid con la lista editable de documentos
Ext.define('RESCATE.grid.DocumentoObra', {
    alias: 'widget.grid.DocumentoObra',
    extend: 'RESCATE.grid.Drop',
    title: t('Associated Documents'),
    soloIconos: true,
    botonAgregar: false,
    formulario: 'editarDocumentoObra',
    mensajeAgregar: t('Add Work Of Art Document'),
    deleteParam1: 'documentoobra_documento_id',
    deleteParam2: 'documentoobra_obra_id',
    nombreRelacion: 'documentoobra_documento_id',
    columns: RESCATE.DocumentoGridColumnas,
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.DocumentoObra', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de documentos
Ext.define('RESCATE.panel.DocumentoObra', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.DocumentoObra',
    title:t( 'Documents'),
    idRelacion: 'campoIdObra',
    gridEditXtype: 'grid.DocumentoObra',
    gridEditItemId: 'gridDocumentoObra',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'DocumentoObraGridDDGroup',
    gridListXtype: 'grid.DocumentoObraLista',
    gridListItemId: 'gridDocumentoObraLista'
});
//formulario editar documentoobra
Ext.define('RESCATE.form.editarDocumentoObra', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarDocumentoObra',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Detalles Documento'),
    maximizable: true,
    initComponent: function ()
    {
		var me = this;
        me.items = [
        {
            xtype: 'form',
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '100%'
            },
            bodyPadding: 10,
            items: [
            {
                xtype: 'hidden',
                name: 'documentoobra_documento_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'hidden',
                name: 'documentoobra_obra_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% -30',
                name: 'documentoobra_detalles'
            }, {
				xtype: 'checkboxfield',
				name: 'documentoobra_portada',
				boxLabel: t('Show as front Image'),
				labelAlign: 'left',
				uncheckedValue: 0
			}],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});