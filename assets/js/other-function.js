function getHariKerja(index){
	var list_hari_kerja = {
		'setiap_hari': 'Setiap Hari',
		'6_hari': 'Senin - Sabtu',
		'5_hari': "Senin - Jum'at",
		'4_hari': 'Senin - Kamis'
	};
	return list_hari_kerja[index];
}

function getHoursAndMinuteOnly(time) {
	var timeSplit = time.toString().split(':');
	var resultTime;
	resultTime = timeSplit[0]+":"+timeSplit[1];
	return resultTime;
}