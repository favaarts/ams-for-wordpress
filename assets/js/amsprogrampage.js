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

  registerBlockType('wpdams-amsnetwork-programpage/amsnetwork-block-programpage', {
    title: i18n.__('AMS Featured Program', 'amsnetwork-gutenbergprogrampage'),
    description: i18n.__('AMS network block setting', 'amsnetwork-gutenbergprogrampage'),
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
     amsprogramid: {
        type: 'string',
     },
     instructors: {
      type: 'boolean',
      default: true
     },
    register_url: {
        type: 'string',
    },
    register_urltab: {
      type: 'string',
      default: '_self',
    },
    showhideurl: {
      type: 'boolean',
      default: true
    },
    member: {
      type: 'boolean',
      default: true
    },
    nonmember: {
      type: 'boolean',
      default: true
    },
    earlybird: {
      type: 'boolean',
      default: true
    },
    type: { type: 'string', default: 'amsprogrampage' },
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
              title: i18n.__('Block Content', 'amsnetwork-gutenbergprogrampage'),
              className: 'block-content',
              initialOpen: true
            },
            el( TextControl,
              {
                label: '(Advanced) Enter Program ID to show one specific project',
                onChange: ( value ) => {
                  props.setAttributes( { amsprogramid: value } );
                },
                value: props.attributes.amsprogramid
              }
            ),
            el( TextControl,
              {
                label: '(Optional) Registration URL',
                onChange: ( value ) => {
                  props.setAttributes( { register_url: value } );
                },
                value: props.attributes.register_url
              }
            ),
            el( SelectControl,
              {
                label: 'Where to open the Registration URL',
                //help: 'Some kind of description',
                options : [
                  { label: 'Same Tab', value: '_self' },
                  { label: 'New Tab', value: '_blank' },
                ],
                onChange: ( value ) => {
                  props.setAttributes( { register_urltab: value } );
                },
                value: props.attributes.register_urltab
              }
            ),
            el('p', {}, i18n.__('( On / Off ) Show Hide register button.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'Display Register Button',
              onChange: ( value ) => {
                 props.setAttributes( { showhideurl: value } );
              },
              checked: props.attributes.showhideurl,
            }),
            el('p', {}, i18n.__('( Show / Hide ) Instructors.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'Instructors',
              onChange: ( value ) => {
                 props.setAttributes( { instructors: value } );
              },
              checked: props.attributes.instructors,
            }),
            el('p', {}, i18n.__('Hide show programs price.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'Member Enrollment Price',
              onChange: ( value ) => {
                 props.setAttributes( { member: value } );
              },
              checked: props.attributes.member,
            }),
            el(ToggleControl, {
              label: 'Non Member Price',
              onChange: ( value ) => {
                 props.setAttributes( { nonmember: value } );
              },
              checked: props.attributes.nonmember,
            }),
            el(ToggleControl, {
              label: 'Earlybird Discount',
              onChange: ( value ) => {
                 props.setAttributes( { earlybird: value } );
              },
              checked: props.attributes.earlybird,
            }),
            
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
           el( 'input', { 'type': 'hidden', 'name' : 'register_url', 'value' : ( props.attributes.register_url) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'register_urltab', 'value' : ( props.attributes.register_urltab) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'showhideurl', 'value' : ( props.attributes.showhideurl) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'member', 'value' : ( props.attributes.member == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'nonmember', 'value' : ( props.attributes.nonmember == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'earlybird', 'value' : ( props.attributes.earlybird == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'amscredentials', 'value' : ( props.attributes.amscredentials == true ? 'yes' : 'no') } ),
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