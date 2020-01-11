(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-1da228cf"],{"2cc0":function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("div",{staticClass:"head-container"},[a("div",[a("el-input",{staticClass:"filter-item",staticStyle:{width:"200px"},attrs:{clearable:"",size:"small",placeholder:"搜索"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.handleQuery(e)}},model:{value:t.query.words,callback:function(e){t.$set(t.query,"words",e)},expression:"query.words"}}),t._v(" "),a("el-button",{staticClass:"filter-item",attrs:{size:"mini",type:"success",icon:"el-icon-search"},on:{click:t.handleQuery}},[t._v("查询")]),t._v(" "),a("el-button",{staticClass:"filter-item",attrs:{size:"mini",type:"primary",icon:"el-icon-plus"},on:{click:t.preCreate}},[t._v("新增")])],1)]),t._v(" "),a("el-divider",[a("i",{staticClass:"el-icon-arrow-down"})]),t._v(" "),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.tableLoading,expression:"tableLoading"}],ref:"table",attrs:{data:t.tableData,"row-key":"id",size:"small"}},[a("el-table-column",{attrs:{"show-overflow-tooltip":!0,prop:"label",label:"名称"}}),t._v(" "),a("el-table-column",{attrs:{"show-overflow-tooltip":!0,prop:"dept",label:"所属部门"}}),t._v(" "),a("el-table-column",{attrs:{prop:"sort",label:"排序"}}),t._v(" "),a("el-table-column",{attrs:{prop:"enabled",label:"是否启用",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return["1"==e.row.enabled?a("span",[t._v("是")]):a("span",[t._v("否")])]}}])}),t._v(" "),a("el-table-column",{attrs:{prop:"update_time",label:"更新日期"}}),t._v(" "),a("el-table-column",{attrs:{label:"操作",width:"130px",align:"center",fixed:"right"},scopedSlots:t._u([{key:"default",fn:function(e){var i=e.row;return[a("el-button",{attrs:{size:"mini",type:"primary",icon:"el-icon-edit"},on:{click:function(e){return t.preUpdate(i.id)}}}),t._v(" "),a("el-button",{attrs:{size:"mini",type:"danger",icon:"el-icon-delete"},on:{click:function(e){return t.doDelete(i.id)}}})]}}])})],1),t._v(" "),a("el-dialog",{attrs:{"append-to-body":"","close-on-click-modal":!1,visible:t.dialogVisible,title:t.dialogActionMap[t.dialogAction],width:"500px"},on:{"update:visible":function(e){t.dialogVisible=e}}},[a("el-form",{ref:"form",attrs:{model:t.formData,rules:t.rules,size:"small","label-width":"80px"}},[a("el-form-item",{attrs:{label:"名称",prop:"label"}},[a("el-input",{model:{value:t.formData.label,callback:function(e){t.$set(t.formData,"label",e)},expression:"formData.label"}})],1),t._v(" "),a("el-form-item",{attrs:{label:"是否启用",prop:"enabled"}},[a("el-radio-group",{attrs:{size:"mini"},model:{value:t.formData.enabled,callback:function(e){t.$set(t.formData,"enabled",e)},expression:"formData.enabled"}},[a("el-radio-button",{attrs:{label:"1"}},[t._v("是")]),t._v(" "),a("el-radio-button",{attrs:{label:"0"}},[t._v("否")])],1)],1),t._v(" "),a("el-form-item",{attrs:{label:"排序",prop:"sort"}},[a("el-input-number",{attrs:{min:0,max:999,"controls-position":"right"},model:{value:t.formData.sort,callback:function(e){t.$set(t.formData,"sort",t._n(e))},expression:"formData.sort"}})],1),t._v(" "),a("el-form-item",{attrs:{label:"所属部门",prop:"dept_id"}},[a("treeSelect",{attrs:{options:t.treeData,placeholder:"选择部门"},model:{value:t.formData.dept_id,callback:function(e){t.$set(t.formData,"dept_id",e)},expression:"formData.dept_id"}})],1)],1),t._v(" "),a("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{attrs:{size:"mini"},on:{click:function(e){t.dialogVisible=!1}}},[t._v("取消")]),t._v(" "),a("el-button",{attrs:{type:"primary",size:"mini"},on:{click:function(e){"create"===t.dialogAction?t.doCreate():t.doUpdate()}}},[t._v("提交")])],1)],1)],1)},l=[],n=(a("ac6a"),a("5df3"),a("ca17")),o=a.n(n),r=(a("542c"),a("c179")),s=a("b775");function c(t){return Object(s["a"])({url:"/api/job",method:"get",params:t})}function d(t){return Object(s["a"])({url:"/api/job",method:"post",data:t})}function u(t){return Object(s["a"])({url:"/api/job",method:"put",data:t})}function f(t){return s["a"].delete("/api/job",{data:{id:t}})}var p=a("980f"),b={name:"AdminJob",components:{treeSelect:o.a},data:function(){return{query:{words:""},tableLoading:!1,tableData:[],treeData:[],dialogVisible:!1,dialogAction:"",dialogActionMap:{update:"编辑",create:"新建"},formData:{id:null,label:"",enabled:"1",sort:999,dept_id:1},rules:{label:[{required:!0,validator:r["a"],trigger:"change"}]}}},created:function(){this.updateTbl(null)},methods:{updateTbl:function(t){this.tableLoading=!0,null===t&&(t={select_col:null,method:null,cond:null,cond_col:null}),c(t).then(function(t){this.tableData.splice(0,this.tableData.length),this.tableData=t.slice(0)}.bind(this)).catch((function(t){console.log(t)})).finally(function(){this.tableLoading=!1}.bind(this))},handleQuery:function(){if(""===this.query.words)this.updateTbl(null);else{var t=Object(r["b"])(this.query.words);if(!0===t){var e={select_col:null,method:"like",cond:{label:this.query.words},cond_col:null};this.updateTbl(e)}else this.$notify.error({title:"错误",message:t,duration:2e3})}},preCreate:function(){this.rstFormData();var t={select_col:"id, label, pid",method:null,cond:null,cond_col:null};c(t).then(function(t){var e=this;this.treeData.splice(0,this.treeData.length),this.treeData=t.slice(0),this.dialogAction="create",this.dialogVisible=!0,this.$nextTick((function(){e.$refs["form"].clearValidate()}))}.bind(this)).catch((function(t){console.log(t)}))},doCreate:function(){var t=this;this.$refs["form"].validate((function(e){e&&d(t.formData).then(function(t){this.dialogAction="",this.dialogVisible=!1,this.updateTbl(null)}.bind(t)).catch((function(t){console.log(t)}))}))},preUpdate:function(t){var e={select_col:"id, label, pid",method:null,cond:null,cond_col:null},a={select_col:null,method:"where",cond:{id:t},cond_col:null};Promise.all([Object(p["c"])(e),Object(p["c"])(a)]).then(function(t){var e=this;this.treeData.splice(0,this.treeData.length),this.treeData=t[0].slice(0),this.copyFormData(t[1][0]),this.dialogAction="update",this.dialogVisible=!0,this.$nextTick((function(){e.$refs["form"].clearValidate()}))}.bind(this)).catch((function(t){console.log(t)}))},doUpdate:function(){var t=this;this.$refs["form"].validate((function(e){if(e){var a=Object.assign({},t.formData);u(a).then(function(t){this.dialogAction="",this.dialogVisible=!1,this.updateTbl(null)}.bind(t)).catch((function(t){console.log(t)}))}}))},doDelete:function(t){var e=this;this.$confirm("确定删除吗？此操作不能撤销！","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning",center:!0}).then((function(){f(t).then(function(t){this.$message({type:"success",message:"删除成功!"}),this.updateTbl(null)}.bind(e)).catch((function(t){console.log(t)}))})).catch((function(){}))},rstFormData:function(){this.formData.id=null,this.formData.label="",this.formData.enabled="1",this.formData.sort=999,this.formData.dept_id=1},copyFormData:function(t){this.formData.id=t.id,this.formData.label=t.label,this.formData.enabled=t.enabled,this.formData.pid=t.pid,this.formData.update_time=t.update_time}}},h=b,m=a("2877"),v=Object(m["a"])(h,i,l,!1,null,null,null);e["default"]=v.exports},"980f":function(t,e,a){"use strict";a.d(e,"c",(function(){return l})),a.d(e,"a",(function(){return n})),a.d(e,"d",(function(){return o})),a.d(e,"b",(function(){return r}));var i=a("b775");function l(t){return Object(i["a"])({url:"/api/dept",method:"get",params:t})}function n(t){return Object(i["a"])({url:"/api/dept",method:"post",data:t})}function o(t){return Object(i["a"])({url:"/api/dept",method:"put",data:t})}function r(t){return i["a"].delete("/api/dept",{data:{id:t}})}},c179:function(t,e,a){"use strict";a.d(e,"a",(function(){return n})),a.d(e,"b",(function(){return o}));var i=/^([0-9a-zA-Z\u4e00-\u9fa5]){2,20}$/,l=/^([0-9a-zA-Z\u4e00-\u9fa5]){2,8}$/;function n(t,e,a){return e&&i.test(e)?void a():a(new Error("请输入中文/英文/数字，长度2~20"))}function o(t){return!t||(!!l.test(t)||"请输入中文/英文/数字，长度2~8")}}}]);