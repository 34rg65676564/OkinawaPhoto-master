( function( blocks, editor, i18n, element, components, _, wp ) {
	var el = element.createElement;
	var Fragment = element.Fragment;
	var RichText = wp.blockEditor.RichText;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var TextControl = components.TextControl;
	var SelectControl = components.SelectControl;
	var PanelBody = components.PanelBody;
	var PanelRow =  components.PanelRow;
	var ExternalLink = components.ExternalLink;
	var BlockControls =  wp.blockEditor.BlockControls;
	var serverSideRender = wp.serverSideRender;
	var Button = wp.components.Button;
	let admin_url = window.gutenberg_rinker.admin_url;
	let designs = window.gutenberg_rinker.designs;

	i18n.setLocaleData( window.gutenberg_rinker.localeData, 'gutenberg-rinker' );

	blocks.registerBlockType( 'rinkerg/gutenberg-rinker', {
		title: i18n.__( 'Rinker', 'gutenberg-rinker' ),
		icon: 'external',
		category: 'layout',
		attributes: {
			content: {
				type: 'array',
				source: 'children',
				selector: 'p',
			},
			content_text: {
				type: 'string',
				default: '',
			},
			alignment: {
				type: 'string',
				default: 'none',
			},
			post_id: {
				type: 'string',
				default: '',
			},
			design: {
				type: 'string',
				default: '',
			},
			title: {
				type: 'string',
				default: '',
			},
			size: {
				type: 'string',
				default: '',
			},
			alabel: {
				type: 'string',
				default: '',
			},
			rlabel: {
				type: 'string',
				default: '',
			},
			ylabel: {
				type: 'string',
				default: '',
			},
			klabel: {
				type: 'string',
				default: '',
			},
			tag: {
				type: 'string',
				default: '',
			},
			className: {
				type: 'string',
				default: '',
			},
		},
		edit: function( props ) {
			let content = props.attributes.content;
			let content_text = Array.isArray(content) ? content[0] : content;
			props.setAttributes( { content_text: content_text } );
			let attributes = props.attributes;
			const clientId = props.clientId;
			let alignment = props.attributes.alignment;
			let design  = props.attributes.design;
			let title   = props.attributes.title;
			let size    = props.attributes.size;
			let alabel  = props.attributes.alabel;
			let rlabel  = props.attributes.rlabel;
			let ylabel  = props.attributes.ylabel;
			let klabel  = props.attributes.klabel;
			var post_id = props.attributes.post_id;
			let tag    = props.attributes.tag;

			//不要なデータが入っていたので削除
			if (props.attributes.className === 'gutenberg-yyi-rinkernone') {
				props.attributes.className = '';
			}

			var ary_atts = [
				'post_id', 'design', 'title', 'size', 'alabel', 'rlabel', 'ylabel', 'klabel', 'tag'
			];

			function onChangeContent( j ) {
				let value = j.target.value;
				props.setAttributes( { content: value } );
				props.setAttributes( { content_text: value } );

				let regexp = new RegExp('post_id=\"(\\S+)\"');
				let att = value.match(regexp);
				if (!!att && !!att[1]) {
					props.setAttributes( { post_id: att[1] } );
				}
			}

			function onChangeDesignField( newValue ) {
				props.setAttributes( { design: newValue } );
			}

			function onChangeTitleField( newValue ) {
				props.setAttributes( { title: newValue } );
			}

			function onChangeSizeField( newValue ) {
				props.setAttributes( { size: newValue } );
			}

			function onChangeAlabelField( newValue ) {
				props.setAttributes( { alabel: newValue } );
			}

			function onChangeRlabelField( newValue ) {
				props.setAttributes( { rlabel: newValue } );
			}

			function onChangeYlabelField( newValue ) {
				props.setAttributes( { ylabel: newValue } );
			}

			function onChangeKlabelField( newValue ) {
				props.setAttributes( { klabel: newValue } );
			}

			function onChangeTagField( newValue ) {
				props.setAttributes( { tag: newValue } );
			}

			let serverSideRenderEl = null;

			if ( content_text ) {
				serverSideRenderEl = el(
					serverSideRender,
					{
						block: 'rinkerg/gutenberg-rinker',
						attributes: attributes,
					}
				);
				let set_attr = {};
				for( var i=0; i < ary_atts.length; i++ ){
					let field = ary_atts[i];
					let regexp = new RegExp(field + '=\"(\\S+)\"');
					let data = props.attributes[field];
					//未選択の項目はショートコードから取得して上書き
					if ( data === '' || data === '0') {
						let att = content_text.match(regexp);
						if (!!att && !!att[1]) {
							set_attr[field] =  att[1];
							props.setAttributes( set_attr );
						}
					}
				}

				//ツールバー
				var toolbarButton = el(
					BlockControls,
					null,
					el(
						components.Toolbar,
						null,
						el(
							Button,
							{
								label: '商品リンク変更',
								icon: 'admin-links',
								className: 'components-button components-icon-button components-toolbar__control yyi-rinker-relink-components-button',
								onClick:  function(j) {
									var url = 'media-upload.php?type=yyi_rinker&tab=yyi_rinker_search_amazon&cid=' + clientId + '&TB_iframe=true';
									tb_show('商品リンク変更', url);
								}
							}
						)
					)
				);
			} else {
				var toolbarButton = null;
			}

			let manager_link_html = el(PanelBody, {title: '商品リンク管理'},
				el(PanelRow, {},
				el( ExternalLink,
					{
						href: admin_url + '?post=' + post_id + '&action=edit',
					}, '商品リンク管理で編集')
			));

			return el(
				Fragment,
				null,
				el(
					'input',
					{
						tagName: 'p',
						className: 'rinkerg-richtext',
						onFocus: onChangeContent,
						onChange: onChangeContent,
						formattingControls: [],
						value: content,
					}
				),
				el(
					'button', {
						className: 'button thickbox add_media',
						onClick: function(j) {
							var url = 'media-upload.php?type=yyi_rinker&tab=yyi_rinker_search_amazon&cid=' + clientId + '&TB_iframe=true';
							tb_show('商品リンク追加', url);
						}
					},
					'商品リンク追加'

				),
				toolbarButton,
				serverSideRenderEl,
				el(
					InspectorControls,
					null,
					el(PanelBody,{title: 'Rinker設定'},
					el(
						SelectControl,
						{
							label: 'デザイン',
							help: 'デザインを選びます',
							value: design,
							options:designs,
							onChange: onChangeDesignField
						}
					),
					el(
						TextControl,
						{
							label: 'タイトル',
							help: 'タイトルを上書きします',
							value: title,
							onChange: onChangeTitleField
						}
					),
					el(
						SelectControl,
						{
							label: '画像サイズ',
							help: '画像サイズを上書きします',
							value: size,
							options:[
								{label: 'デフォルト', value:'0'},
								{label: 'S', value:'s'},
								{label: 'M', value:'m'},
								{label: 'L', value:'l'}],
							onChange: onChangeSizeField
						}
					),
					el(
						TextControl,
						{
							label: 'Amazonボタンの文言',
							help: '',
							value: alabel,
							onChange: onChangeAlabelField
						}
					),
					el(
						TextControl,
						{
							label: '楽天市場ボタンの文言',
							help: '',
							value: rlabel,
							onChange: onChangeRlabelField
						}
					),
					el(
						TextControl,
						{
							label: 'Yahooショッピングボタンの文言',
							help: '',
							value: ylabel,
							onChange: onChangeYlabelField
						}
					),
					el(
						TextControl,
						{
							label: 'Kindleボタンの文言',
							help: '',
							value: klabel,
							onChange: onChangeKlabelField
						}
					),
					el(
						TextControl,
						{
							label: 'AmazonのトラッキングID（個別設定）',
							help: '',
							value: tag,
							onChange: onChangeTagField
						}
					)
					),
					manager_link_html,
				),

			);
		},
		save: function( props ) {
			return el( RichText.Content, {
				tagName: 'p',
				className: props.attributes.className,
				value: props.attributes.content
			} );
		},
		transforms: {
			from: [
				{
					type: 'shortcode',
					tag: 'itemlink',
					attributes: {
						content: {
							type: 'array',
							shortcode: ( attributes, content ) => {
								const itemlink = content.content || '';
								return [itemlink];
							},
						},
					},
				},
			]
		},
	} );

} (
	window.wp.blocks,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element,
	window.wp.components,
	window._,
	wp
) );

