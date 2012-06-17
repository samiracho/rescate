// grid con la lista de noticias
Ext.define('RESCATE.panel.Noticia', {
    alias: 'widget.panel.Noticia',
    extend: 'RESCATE.grid.grid',
    border: false,
    title: t('News List'),
    itemId: 'gridNoticia',
    formulario: 'editarNoticia',
    mensajeAgregar: t('Add Formation'),
    stateful: true,
    stateId: 'statePanelNoticia',
    columns: [
    {
        text: t('Id'),
        dataIndex: 'noticia_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Title'),
        flex: 1,
        dataIndex: 'noticia_titulo',
        sortable: true
    }
/*, {
        text: t('Body'),
        flex: 1,
        dataIndex: 'noticia_cuerpo',
        sortable: true
    }*/
    , {
        text: t('Date'),
        dataIndex: 'noticia_fecha',
        sortable: true
    }, {
        text: t('Published') + ' (0/1)',
        dataIndex: 'noticia_publicada',
        sortable: true,
        renderer: function (val)
        {
            if (val == "1") return '<b>' + t('Yes') + '</b>';
            else
            return t('No')
        }
    }
/*, {
        text: t('In Front Page'),
        dataIndex: 'noticia_portada',
        sortable: true,
        renderer: function (val)
        {
            if (val == "1") return '<b>' + t('Yes') + '</b>';
            else
            return t('No')
        }
    }*/
    , {
        text: t('Author'),
        dataIndex: 'usuario_nombre',
        sortable: true
    }, {
        text: t('Surname'),
        dataIndex: 'usuario_apellido1',
        sortable: true,
        hidden: true
    }, {
        text: t('Surname 2'),
        dataIndex: 'usuario_apellido2',
        sortable: true,
        hidden: true
    }, {
        text: t('Login'),
        dataIndex: 'usuario_login',
        sortable: true,
        hidden: true
    }, {
        text: t('Last Modification'),
        dataIndex: 'noticia_ultimamod',
        sortable: true,
        hidden: true,
        searchable: false
    }],
    // propiedades adicionales de nuestro grid personalizado
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Noticia', {
        });
        this.callParent(arguments);
    }
});
Ext.define('RESCATE.form.editarNoticia', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarNoticia',
    height: 550,
    minHeight: 550,
    closeAction: 'destroy',
    width: 700,
    minWidth: 600,
    layout: {
        type: 'fit'
    },
    title: t('News'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        var creadorStore = Ext.create('widget.store.Creador', {
        });
        me.items = [
        {
            xtype: 'form',
            layout: {
                align: 'stretch',
                type: 'vbox'
            },
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '100%'
            },
            bodyPadding: 10,
            title: '',
            items: [
            {
                xtype: 'container',
                layout: {
                    type: 'column'
                },
                items: [
                {
                    xtype: 'container',
                    layout: {
                        type: 'anchor'
                    },
                    columnWidth: 0.5,
                    items: [
                    {
                        xtype: 'hiddenfield',
                        name: 'noticia_id'
                    }, {
                        xtype: 'textfield',
                        name: 'noticia_titulo',
                        fieldLabel: t('Title') + '*',
                        allowBlank: false,
                        anchor: '96%'
                    }, {
                        xtype: 'checkboxfield',
                        name: 'noticia_portada',
                        disabled: true,
                        boxLabel: t('In Front Page'),
                        uncheckedValue: 0
                    }]
                }, {
                    xtype: 'container',
                    layout: {
                        type: 'anchor'
                    },
                    columnWidth: 0.5,
                    items: [
                    {
                        xtype: 'form.DateField',
                        name: 'noticia_fecha',
                        fieldLabel: t('Date') + '*',
                        allowBlank: false
                    }, {
                        xtype: 'checkboxfield',
                        name: 'noticia_publicada',
                        boxLabel: t('Published'),
                        uncheckedValue: 0
                    }]
                }]
            }, {
                xtype: 'container',
                layout: {
                    type: 'anchor'
                },
                flex: 1,
                items: [
                {
                    xtype: 'form.ComboBox',
                    displayField: 'nombre',
                    valueField: 'id',
                    name: 'noticia_usuario_id',
                    store: creadorStore,
                    fieldLabel: t('Creator'),
                    emptyText: t('Me')
                }, {
                    xtype: 'htmleditor',
                    plugins: [new Ext.create('Ext.ux.form.HtmlEditor.imageUpload', {
                        submitUrl: 'datos/htmlEditorImageUpload.php',
                        managerUrl: 'datos/htmlEditorImageUpload.php',
                        lang: {
                            'Display': 'Mostrar',
                            'By Default': 'Por Defecto',
                            'Inline': 'En línea con el texto',
                            'Block': 'En una línea a parte',
                            'Insert/Edit Image': 'Insertar/Editar Imagen',
                            'Upload Image...': 'Subir Imagen...',
                            'Uploading your photo...': 'Subiendo su imagen...',
                            'Error': 'Error',
                            'Width': 'Ancho',
                            'Height': 'Alto',
                            'Align': 'Alineación',
                            'Title': 'Título',
                            'Class': 'Clase',
                            'Padding': 'Relleno',
                            'Margin': 'Margen',
                            'Top': 'Superior',
                            'Bottom': 'Inferior',
                            'Right': 'Derecha',
                            'Left': 'Izquierda',
                            'None': 'Ninguna',
                            'Size & Details': 'Tamaño y Detalles',
                            'More Options': 'Más Opciones',
                            'Style': 'Estilo',
                            'OK': 'Aceptar',
                            'Cancel': 'Cancelar',
                            'Delete Image': 'Borrar Imagen',
                            'Confirmation': 'Confirmación',
                            'Are you sure you want to delete this image?': '¿Está seguro de que desea eliminar esta imagen?',
                            'Your photo has been uploaded.': 'Su imagen ha sido subida.'
                        }
                    })],
                    name: 'noticia_cuerpo',
                    fieldLabel: t('Details'),
                    labelAlign: 'top',
                    anchor: '100% 100%',
                    margin: '0 1 50 0'
                }]
            }],
            dockedItems: me.barraBotones()
        }];
        // Datoscargados es un evento que se dispara cuando el record ya ha sido cargado en los campos del formulario
        me.addListener('datoscargados', function (component)
        {
            creadorStore.load();
        });
        me.callParent(arguments);
    }
});