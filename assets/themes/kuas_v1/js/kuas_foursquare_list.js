/*
 * Kuas Foursquare List
 * Versi 1.0
 * 4sq API data JSON (see: https://foursquare.com/wisatakuningan)
 * Prototype version build 1
 * Modified by Ofan Ebob (Sept'13) from stackflow (about jquery JSON get problem with IE)
 * Combine code with primitive javascript metode looping strings JSON results
 */
function render_recomended_post_venue(e,t,n){
	if(t==true){
	var r='<div id="recomended_post_venue"><h4 class="bgElement">Rekomendasi Tempat Kuningan</h4><ul id="list_recomended_post_venue">';
		if(foursquare_post_venue_tmp.post_venue.length>0){
			for(i=0;i<15;i++){
				var s=foursquare_post_venue_tmp.post_venue[i];
				vanue_icon=s.vdata.vicon.replace(/bg_32/g,"32");
				var o="'"+s.vid+"','"+s.vdata.vlat+"','"+s.vdata.vlng+"','"+s.vdata.vtitle+"','"+s.vdata.vicon+"','"+s.vdata.vimg+"','"+s.vdata.vaddress+"','"+s.vdata.vcity+"','"+s.vdata.vstate+"','"+s.vdata.vscore+"','"+s.vdata.vlink+"','"+s.vdata.vcatinit+"','"+s.vdata.vstats+"','"+parent_venue+"','"+s.vdata.vurl+"','"+s.vdata.vclass+"'";
				r+='<li><a href="javascript:void(0);" onclick="show_venue_recomended_poin('+o+","+n+');"><img src="'+vanue_icon+'" />'+s.vdata.vname+"</a></li>"
			}
		}
		r+="</ul></div>";
		jQuery(r).appendTo(e)
	}
	else{
		jQuery("#recomended_post_venue").fadeOut().remove()
	}
}

function addmarker_recomended_post_venue(e,t){
	var n=jQuery.inArray(t,venue_id_tmps);
	if(n!=-1){
		var r=foursquare_post_venue_tmp.post_venue[n];
		show_venue_recomended_poin(r.vid,r.vdata.vlat,r.vdata.vlng,r.vdata.vtitle,r.vdata.vicon,r.vdata.vimg,r.vdata.vaddress,r.vdata.vcity,r.vdata.vstate,r.vdata.vscore,r.vdata.vlink,r.vdata.vcatinit,r.vdata.vstats,parent_venue,r.vdata.vurl,r.vdata.vclass,e)
	}
}

function show_venue_recomended_poin(e,t,n,r,i,s,o,u,a,f,l,c,h,p,d,v,m){
	if(m==true){
		jQuery(p).gmap("clear","markers");
		jQuery("select#spot_kuningan").val("0").trigger("change");
		var g=jQuery("form#search_nearby_venue");
		g.find('input[name="ll"]').val(t+","+n);
		var y=g.find("input#search_nearby_venue_query");
		y.attr("placeholder","Tempat Sekitaran "+r)
	}

	BuildMarkerFoursquare(e,t,n,r,i,s,o,u,a,f,l,c,h,p,d,v);

	if(m==true){
		jQuery(p).gmap("find","markers",{property:"id",value:e},function(e,t){jQuery(e).triggerEvent("click")});
		jQuery(p).gmap("option","center",new google.maps.LatLng(parseFloat(t)+.001,n))
	}
		return false
}
	
function BuildResponseTemp(e,t){
	var n=Array();
	var r=Array();
	var s=[];
	for(i=0;i<e.length;i++){
		var o=e[i].venue?e[i].venue:"";
		if(e.length>0&&o!=""){
			s="200";var u=e[i].score;
			var a=o.venue_id?o.venue_id:"";
			var f=o.venue_name;
			var l=e[i].title.replace(/[^a-z0-9\s]/gi,"");
			var c=e[i].thumbnail?e[i].thumbnail:kuas_ajax_var.kuas_domain+"/images/photo-placeholder.jpg";
			var h=o.venue_url?o.venue_url:kuas_ajax_var.kuas_domain;
			var p=e[i].link;
			var d=o.venue_checkin?o.venue_checkin:0;
			var v=o.venue_users?o.venue_users:0;
			var m=d?parseInt(o.venue_checkin+o.venue_users):"";
			var g=d?Math.round(m/2):"";
			var y=o.venue_icon;
			var b=o.venue_category_name?o.venue_category_name:"";
			var w=o.venue_category_id?o.venue_category_id:"";
			var E=o.venue_city?o.venue_city:"";
			var S=o.venue_state?o.venue_state:"";
			var x=o.venue_address?o.venue_address:"";
			var T=o?o.venue_lat:"";
			var N=o?o.venue_lng:"";
			var C="marker-post";
			r.push('"'+a+'"');
			n.push('{"vid":"'+a+'","vdata":{"vname":"'+f+'","vtitle":"'+l+'","vimg":"'+c+'","vurl":"'+p+'","vlink":"'+h+'","vlat":"'+T+'","vlng":"'+N+'","vclass":"'+C+'","vstats":"'+m+'","vscore":"'+g+'","vicon":"'+y+'","vcatinit":"'+b+'","vcatid":"'+w+'","vstate":"'+S+'","vaddress":"'+x+'","vcity":"'+E+'","vscore":"'+g+'","vpoin":"'+u+'"}}');
		}
		else{
			s="500";
		}
	}

	if(s=="200"){
		result_venue_tmp='{"post_venue":['+n.join(",")+'],"code":200}';
		render_venue_id_tmp="var venue_id_tmps = ["+r.join(",")+"];"
	}
	else{
		result_venue_tmp='{"post_venue":[],"code":500}';
	}

	jQuery('<script type="text/javascript">/* <![CDATA[ */ \r\n '+render_venue_id_tmp+" \r\n var "+t+" = "+result_venue_tmp+"; \r\n /* ]]> */</script>").appendTo("head");
}

function BuildOpenWindow(e,t,n,r,i,s,o,u,a,f,l,c,h,p,d,v,m){
	var g='<div id="box_info_tempat" class="box-type-'+m+'" data-id="'+t+'" data-geo="'+n+","+r+'">';
	if(m.match(/marker-labels/g)){
		var y=Math.floor(Math.random()*kuas_pariwara_pin.pariwara.length);
		var b=kuas_pariwara_pin.pariwara[y];
		g+='<a class="kuas-pariwara" href="'+b.url+'" target="_blank" title="" rel="nofollow">';
		g+='<img src="'+b.img+'" alt="" width="250" /></a><div class="clear"></div>';
	}

	if(o!=0){
		g+='<a href="'+v+'" target="_blank" class="url-image-venue display-inline-block"><img src="'+o+'" align="absmiddle" /></a>';
		g+='<span class="content_meta_v"><h5 class="nobackground-color">'+p+"+ "+people_here_text+":</h5>";
	}

	g+='<h3 class="nobackground-color">'+limitTitleWords(i,4)+"</h3>";

	if(o!=0){
		g+=u+" "+a+" "+f+"<br />";
	}

	g+=recomended_text+": "+l+" "+people_obj_text+"<br />";

	if(m.match(/marker-post/g)){
		addClasses="by_post";
	}
	else{
		addClasses="by_foursquare";
	}

	g+='&#10149; <a href="'+v+'" class="achor-marker-map '+addClasses+'" id="'+t+'" target="_blank">'+view_information_text+"...</a>";

	if(o!=0){
		g+='<br /><br /><hr class="gradient-transparent">';
		g+='<span class="foot_meta_v">sumber: <a href="'+c+'" target="_blank">4Square</a></span>';
	}
	g+="</span></div>";
	jQuery(d).gmap("openInfoWindow",{content:g},e);
}
		
function BuildMarkerFoursquare(e,t,n,r,i,s,o,u,a,f,l,c,h,p,d,v){
	if(v.match(/high-score/g)){
		title=r+" (Tempat Populer)";
	}
	else if(v.match(/marker-post/g)){
		title=r+" (Tempat Rekomendasi)"
	}
	else{
		title=r;
	}
	if(h!=0){
		jQuery(p).gmap("addMarker",{
			id:e,position:new google.maps.LatLng(t,n),
			tags:c,
			title:title,
			icon:i,
			optimized:false,
			labelAnchor:new google.maps.Point(23,37),
			labelClass:v,
			labelStyle:{opacity:1},
			labelVisible:true,
			marker:MarkerWithLabel}).click(function(){
				BuildOpenWindow(this,e,t,n,r,i,s,o,u,a,f,l,c,h,p,d,v);
			});
			maps_loading("stop");
	}
}

function BuildFailConnection(e,t){
	jQuery(e).html('<div class="error_4square_maps">Oops, kesalahan '+t+" </div>");maps_loading("stop");
}

jQuery(document).ready(function(){
	jQuery(parent_venue).gmap({disableDefaultUI:true,center:SudutKordinat,zoom:ZoomPeta,styles:[{stylers:[{gamma:.75},{lightness:-5},{saturation:20}]}]}).live("init",function(){
		jQuery.getJSON(kuas_ajax_var.kuas_ajax_url,{action:"post_venue",type:"post",cat:"",ordby:"rand",limit:"15",order:"DESC"}).done(function(e){
			if(e.code==200){
				BuildResponseTemp(e.post_venue,"foursquare_post_venue_tmp");
				var t='<form action="https://api.foursquare.com/v2/venues/search" method="post" id="search_nearby_venue">';
				t+='<label for="query">'+search_text+"</label>";
				t+='<input type="text" id="search_nearby_venue_query" name="query" size="40" placeholder="'+searching_nearby+'">';
				t+='<select name="venue_categories" id="venue_categories">';
				t+='<option value="0">'+select_category_text+"...</option>";
				jQuery.each(foursquare_categories.fsqcat,function(e,n){
					t+='<option value="'+n.id+'" title="'+n.name+'">'+n.name+"</option>"});
					t+="</select>";
					t+='<input type="hidden" name="ll" value="'+SudutKordinat+'">';
					t+="</form>";
					jQuery(t).css({display:"none"}).appendTo("#containner_top");

					jQuery("form#search_nearby_venue").on("submit",function(e){
						e.preventDefault();
						jQuery(parent_venue).gmap("clear","markers");
						jQuery(parent_venue).gmap("closeInfoWindow");
						var t=jQuery(this).find("input#search_nearby_venue_query");
						var n=jQuery(this).attr("action"),r=t.val();
						var i=jQuery(this).find('input[name="ll"]').val(),s=i.split(",");
						var o=kuas_ajax_var.kuas_ajax_date,u=kuas_ajax_var.kuas_locale.split("_");
						var a=t.attr("placeholder");
						maps_loading("start",searching_text+" "+t.val()+" "+a);
						var f=jQuery(this).find("select#venue_categories > option:selected");
						var l=f.val();

						if(l!=0||l!="0"){
							var c="&categoryId="+l;
						}
						else{
							var c="";
						}

						if(r.length>0||r!=""){
							var h="query="+encodeURIComponent(r)+"&";
							var p="&radius=800&limit=50";
							var d="&intent=browse";
							var v=3
						}
						else{
							var h="";
							var p="&radius=500&llAcc=550&limit=25";
							var d="&intent=checkin";
							var v=20;
						}

						var m=n+"?"+h+"ll="+i+"&v="+o+"&locale="+u[0]+"&client_id="+maps_clid+"&client_secret="+maps_clsc+c+d+p;

						jQuery.getJSON(m).done(function(e){
							jQuery(parent_venue).gmap("clear","markers");
							if(e.response.venues.length>0){
								var n=[];
								var i=[];
								jQuery.each(e.response.venues,function(e,r){
									i.push(r.id);
									var s=parseInt(r.stats.checkinsCount+r.stats.usersCount);
									var o=Math.round(s/2);
									if(r.stats.usersCount>=v&&jQuery.inArray(r.id,venue_id_tmps)==-1){
										t.val("");
										var u=r.id;
										var a=r.name.split(",").join(" ").replace(/[^a-z0-9 \-\s]/gi,"");
										n.push(u);
										var f=r.categories.length;
										for(var l=0;l<f;l++){
											var f=r.categories[l];
										}

										var c=f?f.icon.prefix+"bg_32"+f.icon.suffix:kuas_ajax_var.kuas_domain+"/assets/images/none_bg_32.png";
										var h=f?f.id:"";
										var p=f?f.shortName:"";
										var m="https://id.foursquare.com/venue/"+u;
										var g=r.location;
										var y=g.address?g.address:"";
										var b=g.city?g.city:"";
										var w=g.state?g.state:"";
										var E=g.country?g.country:"";
										var S=r.location.lat;
										var x=r.location.lng;
										var T=0;
										if(r.stats.usersCount>=100){
											var N="marker-labels high-score";
										}
										else if(r.stats.usersCount>=15){
											var N="marker-labels middle-score";
										}
										else{
											var N="marker-labels low-score";
										}

										BuildMarkerFoursquare(u,S,x,a,c,T,y,b,w,o,m,p,s,parent_venue,m,N);
									}
									else{
										maps_loading("stop");
									}
								});

								jQuery.each(i,function(e,t){
									return addmarker_recomended_post_venue(false,t);
								});

								var s=jQuery.inArray(getRandomArr(n),i);
								if(n.count()>=10){
									jQuery(parent_venue).gmap("option","zoom",15);
								}
								else{
									if(s!=-1){
										jQuery(parent_venue).gmap("option","zoom",17);
									}
								}

								if(s!=-1){
									var o=e.response.venues[s];
								}
								else{
									var o=e.response.venues[0];
								}

								if(r.length==0||r==""){
									jQuery(parent_venue).gmap("option","center",new google.maps.LatLng(o.location.lat+.001,o.location.lng));
									jQuery(parent_venue).gmap("find","markers",{property:"id",value:o.id},function(e,t){
										jQuery(e).triggerEvent("click");
									});
								}
								else{
									jQuery(parent_venue).gmap("option","center",new google.maps.LatLng(o.location.lat,o.location.lng));
								}
							}
							else{
								maps_loading("stop");
							}
						})
						.fail(function(){
							return BuildFailConnection(parent_venue,"koneksi");
						});
					});

					var n='<select name="spot_kuningan" id="spot_kuningan">';
					n+='<option value="0" disabled>'+select_location_text+"...</option>";
					jQuery.each(center_LL,function(e,t){
						n+='<option value="'+t[0]+'" rel="'+t[1]+'">'+nearby_text+": "+t[1]+"</option>"});
						n+="</select>";
						jQuery(n).appendTo("#containner_top");
						jQuery("select#spot_kuningan").live("change",function(){
							jQuery("option:selected",this).each(function(){
								var e=jQuery(this);
								var t=jQuery(this).val();
								var n=t.split(",");
								var r=n[0];
								var i=n[1];
								var s=jQuery("form#search_nearby_venue");
								s.find('input[name="ll"]').val(t);
								var o=s.find("input#search_nearby_venue_query");
								o.attr("placeholder",e.text());
								if(t!=0){
									s.trigger("submit");
								}
							});
						});

						jQuery("select#spot_kuningan").val(SudutKordinat).trigger("change");
						jQuery("div#more_venue").clickToggle(function(){
							jQuery(container_venue).css("float","none").animate({height:Math.round(window.innerHeight-window.innerHeight*12/100)+"px"},function(){
								jQuery("div.sidetop").fadeOut(function(){
									jQuery(parent_venue).gmap("closeInfoWindow");
									jQuery(parent_venue).gmap("refresh","clear");
									jQuery("form#search_nearby_venue").css({display:"inline"});
									jQuery("select#spot_kuningan").css({top:"2px",bottom:"inherit","border-width":"1px"});
									render_recomended_post_venue(container_venue,true,true);
								});
							});

							jQuery(this).addClass("zoomOut").removeClass("zoomIn").find("i").text(zoomout_text)},function(){
								render_recomended_post_venue(container_venue,false,true);
								jQuery("form#search_nearby_venue").css({display:"none"});
								jQuery("select#spot_kuningan").css({bottom:"30px",top:"inherit","border-width":"3px"});jQuery(container_venue).animate({height:"300px"},function(){
									jQuery("div.sidetop").fadeIn(function(){
										jQuery(parent_venue).gmap("closeInfoWindow");
										jQuery(parent_venue).gmap("refresh","clear")});
										jQuery(this).css("float","left");
								});

								jQuery(this).addClass("zoomIn").removeClass("zoomOut").find("i").text(zooming_text)}).addClass("active");
							}
							else{
								return BuildFailConnection(parent_venue,"(code: <b>"+e.code+"</b>)");
							}
						})
						.fail(function(){
							return BuildFailConnection(parent_venue,"("+failed_load_text+")");
						});
					});
				});