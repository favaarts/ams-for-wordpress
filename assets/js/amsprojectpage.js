(function(blocks, editor, components, i18n, element) {
  var el = element.createElement;
  var registerBlockType = blocks.registerBlockType;
  var RichText = editor.RichText;
  var BlockControls = editor.BlockControls;
  var AlignmentToolbar = editor.AlignmentToolbar;
  var MediaUpload = editor.MediaUpload;
  var InspectorControls = editor.InspectorControls;
  var TextControl = components.TextControl;
  var ToggleControl = components.ToggleControl;
  var RadioControl = components.RadioControl;
  var SelectControl = components.SelectControl;

  registerBlockType('wpdams-amsnetwork-projectpage/amsnetwork-block-projectpage', {
    title: i18n.__('AMS Project Page', 'amsnetwork-gutenbergprojectpage'),
    description: i18n.__('AMS network block setting', 'amsnetwork-gutenbergprojectpage'),
    icon: 'screenoptions',
    category: 'common',
    attributes: {
      mediaID: {
        type: 'number'
      },
      mediaURL: {
        type: 'string',
        source: 'attribute',
        selector: 'img',
        attribute: 'src'
      },
     amsprojectid: {
        type: 'string',
     },
     project_protected: {
        type: 'string',
     },
     amsfile_attachment: {
        type: 'boolean',
        default: false
     },
     project_paymenturl: {
        type: 'string',
     },
    project_paymentmessage: {
        type: 'string',
     },
     paymentbuttonname: {
        type: 'string',
     },
     mailsubject: {
        type: 'string',
     },
     senderemailaddress: {
        type: 'string',
     },
     firstpartmailtext: {
        type: 'string',
     },
     secondpartmailtext: {
        type: 'string',
     },
     projecttomember: {
      type: 'boolean',
      default: false
     },
    amscredentials: {
      type: 'boolean',
      default: false
    },
    amsprojectpagesidebar: {
      type: 'boolean',
      default: false
    },
    type: { type: 'string', default: 'amsprojectpage' },
      alignment: {
        type: 'string',
        default: 'center'
      }
    },
    edit: function(props) {

     function updateContent( newdata ) {
            props.setAttributes( { content: newdata } );
         }  

      var radioField = props.attributes;
      function onChangeRadioField( newValue ) {
        props.setAttributes( { radioField: newValue } );
      }

      var attributes = props.attributes;
      var onSelectImage = function(media) {
        return props.setAttributes({
          mediaURL: media.url,
          mediaID: media.id
        })
      };
      return [
        
        el(InspectorControls, {
            key: 'inspector'
          },
          el(components.PanelBody, {
              title: i18n.__('Block Content', 'amsnetwork-gutenbergprojectpage'),
              className: 'block-content',
              initialOpen: true
            },          
            el(ToggleControl, {
              label: 'Connect Projects to Members',
              onChange: ( value ) => {
                 props.setAttributes( { projecttomember: value } );
              },
              checked: props.attributes.projecttomember,
            }),
            el(ToggleControl, {
              label: 'Allow Users to Download Media?',
              onChange: ( value ) => {
                 props.setAttributes( { amsfile_attachment: value } );
              },
              checked: props.attributes.amsfile_attachment,
            }),
            el(ToggleControl, {
              label: 'Restrict Videos with AMS Credentials',
              onChange: ( value ) => {
                 props.setAttributes( { amscredentials: value } );
              },
              checked: props.attributes.amscredentials,
            }),
            el(ToggleControl, {
              label: 'Sidebar',
              onChange: ( value ) => {
                 props.setAttributes( { amsprojectpagesidebar: value } );
              },
              checked: props.attributes.amsprojectpagesidebar,
            }),
            el( TextControl,
              {
                label: '(Advanced) Enter Project ID to show one specific project',
                onChange: ( value ) => {
                  props.setAttributes( { amsprojectid: value } );
                },
                value: props.attributes.amsprojectid
              }
            ),
            el( TextControl,
              {
                label: 'Password for Protecting Content (Leave Blank if Public)',
                onChange: ( value ) => {
                  props.setAttributes( { project_protected: value } );
                },
                value: props.attributes.project_protected
              }
            ),
            el( TextControl,
              {
                label: 'Payment URL (Leave Blank for No Payment)',
                onChange: ( value ) => {
                  props.setAttributes( { project_paymenturl: value } );
                },
                value: props.attributes.project_paymenturl
              }
            ),
             el( TextControl,
              {
                label: 'Payment Text Message',
                onChange: ( value ) => {
                  props.setAttributes( { project_paymentmessage: value } );
                },
                value: props.attributes.project_paymentmessage
              }
            ),
            el( TextControl,
              {
                label: 'Payment Button Name',
                onChange: ( value ) => {
                  props.setAttributes( { paymentbuttonname: value } );
                },
                value: props.attributes.paymentbuttonname
              }
            ),
            el( TextControl,
              {
                label: 'Change the email subject line',
                onChange: ( value ) => {
                  props.setAttributes( { mailsubject: value } );
                },
                value: props.attributes.mailsubject
              }
            ),
            el( TextControl,
              {
                label: 'Change the sender email address (Leave blank for default)',
                onChange: ( value ) => {
                  props.setAttributes( { senderemailaddress: value } );
                },
                value: props.attributes.senderemailaddress
              }
            ),
            el( TextControl,
              {
                label: 'Change the first part of the email',
                onChange: ( value ) => {
                  props.setAttributes( { firstpartmailtext: value } );
                },
                value: props.attributes.firstpartmailtext
              }
            ),
            el( TextControl,
              {
                label: 'Change the last part of the email',
                onChange: ( value ) => {
                  props.setAttributes( { secondpartmailtext: value } );
                },
                value: props.attributes.secondpartmailtext
              }
            ),
            
            
            
            
          )
        ),
        el( 'div',
            {
               className: 'amsblock-box amsblock-' + props.attributes.type
            },
            
            el(
               wp.editor.RichText,
               {
                  tagName: 'p',
                  onChange: updateContent,
                  value: '['+props.attributes.type+']'
               }
            )

         ),
        
      ];
    },
    save: function(props) {
      var attributes = props.attributes;
      return (
        el( 'div',
            {
               className: 'amsblock-box amsblock-' + props.attributes.type
           },
           el(
               'h4',
               null,
               props.attributes.title
           ),
           el('div', {className: 'header-right-part wp-block-shortcode'},
                           el( wp.element.RawHTML, null, '['+props.attributes.type+']')
           ),
           el( 'input', { 'type': 'hidden', 'name' : 'projecttomember', 'value' : ( props.attributes.projecttomember == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'amscredentials', 'value' : ( props.attributes.amscredentials == true ? 'yes' : 'no') } ), 
           el( 'input', { 'type': 'hidden', 'name' : 'project_protected', 'value' : ( props.attributes.project_protected) } ),
         )

      )
    }
  })
})(
  window.wp.blocks,
  window.wp.editor,
  window.wp.components,
  window.wp.i18n,
  window.wp.element
);