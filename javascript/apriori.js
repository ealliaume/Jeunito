"use baskets"

generate_one_itemset = function(set) {
	var temp = [];
	
	set.forEach(function(item) {
		temp.push([item]);
	});

	return temp;
}

get_set_cpy = function(set) {
	var cpy = [];

	set.forEach(function(item) {
		cpy.push(item);
	});	

	return cpy;
}

generate_k_subsets = function(set, k) {
	if (k == 1) return generate_one_itemset(set);
        
	var kminus_one_subsets = generate_k_subsets(set, k-1);
	var k_subsets = [];

	for (var c = 0; c < kminus_one_subsets.length; c++) {
		for (var d = c+1; d < set.length; d++) {
			var curr_set = kminus_one_subsets[c];
			var curr_itm = set[d];
		
			if (curr_set == curr_itm) continue;
			if (curr_set.indexOf(curr_itm) >= 0 ) continue;

			var set_cpy = get_set_cpy(curr_set);
			set_cpy.push(curr_itm);
			
			k_subsets.push(set_cpy);
		}
	} 
		
	return k_subsets;		
}

list_items = function() {
	var m = function() {
		for (item in this.basket) {
			emit(this.basket[item], null);
		}
	}
		
	var r = function(k, v) {
		return k;
	};
	
	db.baskets.mapReduce(m, r, { 'out' : 'items' });
	
	var items = [];	
	
	db.items.find().forEach(function(i){
		items.push(i._id);
	});
	
	return items;
}

generate_basket_subsets = function() {
	var subsets = generate_k_subsets(this.basket, set_size);
	//var subsets = [ ['milk', 'cheese'], ['milk', 'eggs'] ];
	for (i in subsets) {
		var key = subsets[i].join(',');
		emit(key, 1);
	}
}

generate_candidate_items = function(set_size) {	
	var m = generate_basket_subsets; 
	var r = function(k, v) {
		var count = 0;

		for(index in v) { 
			count+=v[index];
		}
	
		return count;
	};

	var out = 'c' + set_size;
	db.baskets.mapReduce(m, r, { 
		'out' : out, 
		'scope' : 
			{
				set_size : set_size, 
				generate_one_itemset : generate_one_itemset, 
				get_set_cpy : get_set_cpy,
				generate_k_subsets : generate_k_subsets,   
			}  
	}); 	
}


generate_candidate_items(1)

