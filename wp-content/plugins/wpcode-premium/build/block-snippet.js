(window.WPCodeBlockSnippet||function(e,t,s){const{registerBlockType:i}=t.wp.blocks,{serverSideRender:n=wp.components.ServerSideRender}=wp,{createElement:p}=wp.element,{InspectorControls:o}=wp.blockEditor||wp.editor,{strings:r,can_edit_snippets:l}=wpcode_gutenberg_snippet,{SelectControl:a,PanelBody:u,TextControl:c}=wp.components,{useSelect:d}=wp.data,b={init:function(){b.registerBlock()},registerBlock:function(){i("wpcode/snippet",{title:r.title,description:r.description,keywords:r.block_keywords,icon:b.getIcon(),supports:{html:!1,className:!1,customClassName:!1},edit:function(e){const t=[],s=e.attributes,i=wp.data.select("core/editor").getCurrentPostId(),{snippets:b}=d((e=>{const{getEntityRecords:t}=e("core");return{snippets:t("postType","wpcode",{status:["publish","draft"],per_page:100,exclude:i})}}));let h=[];b?(h.push({value:"",label:r.select_snippet}),b.sort(((e,t)=>"publish"===e.status&&"publish"!==t.status?-1:"publish"!==e.status&&"publish"===t.status?1:0)),b.forEach((e=>{h.push({value:e.id,label:e.title.raw+("publish"===e.status?"":" - ("+r.inactive+")"),shortcode_attributes:e.shortcode_attributes})}))):h.push({value:0,label:r.loading}),e.attributes.snippetId?t.push(p(n,{key:"wpcode-snippet-server-side-renderer",block:"wpcode/snippet",attributes:e.attributes})):l?t.push(p("div",{className:"wpcode-gutenberg-snippet-select",style:{padding:"1rem"}},p(a,{label:r.choose_snippet,value:s.snippetId,options:h,onChange:t=>e.setAttributes({snippetId:t})}))):t.push(p("p",null,r.no_permission));const g=b&&b.find((t=>t.id===parseInt(e.attributes.snippetId)));return l?t.push(p(o,{key:"wpcode-snippet-inspector-main-settings"},p(u,{className:"wpcode-gutenberg-panel",title:r.snippet_settings},p(a,{label:r.choose_snippet,value:s.snippetId,options:h,onChange:t=>e.setAttributes({snippetId:t,attributes:{}})})),s.snippetId&&g&&g.shortcode_attributes&&g.shortcode_attributes.length>0&&p(u,{className:"wpcode-gutenberg-panel",title:r.snippet_attributes},g.shortcode_attributes.map((t=>{const i=s.attributes&&s.attributes[t]?s.attributes[t]:"";return p(c,{key:"attr_"+t,label:t,value:i,onChange:i=>((t,i)=>{const n={...s.attributes};n[t]=i,e.setAttributes({attributes:n})})(t,i)})}))))):t.push(p(o,{key:"wpcode-snippet-inspector-main-settings"},p(u,{className:"wpcode-gutenberg-panel",title:r.snippet_settings},p("p",null,r.no_permission)))),t},save:()=>null})},getIcon:function(){return p("svg",{width:20,height:20,viewBox:"0 0 65 64",className:"dashicon"},p("path",{fill:"currentColor",fillRule:"evenodd",clipRule:"evenodd",d:"M58.0373 64H7.03399C3.36653 64 0.466675 61.1064 0.466675 57.4468V6.55319C0.466675 2.89362 3.36653 0 7.03399 0H58.0373C61.7048 0 64.6046 2.89362 64.6046 6.55319V57.4468C64.6046 61.1064 61.7048 64 58.0373 64ZM16.3297 52.0855C15.9886 52.0855 15.5621 52.0004 15.221 51.9153C13.6857 51.3196 12.9181 49.6175 13.5152 48.0855L26.9057 13.7877C27.5027 12.2558 29.2085 11.4898 30.7437 12.0855C32.2789 12.6813 33.0465 14.3834 32.4495 15.9153L19.1443 50.2132C18.7178 51.4047 17.5238 52.0855 16.3297 52.0855ZM35.52 47.7445C36.1171 48.3403 36.8847 48.5956 37.6523 48.5956C38.4199 48.5956 39.1875 48.3403 39.7845 47.7445L50.2752 37.3616C52.1516 35.4892 52.1516 32.3403 50.2752 30.4679L39.7845 19.9999C38.6758 18.8084 36.7994 18.8084 35.6053 19.9999C34.4113 21.1063 34.4113 22.9786 35.6053 24.1701L45.2431 33.8722L35.52 43.5743C34.326 44.6807 34.326 46.5531 35.52 47.7445Z"}))}};return b}(document,window,jQuery)).init();