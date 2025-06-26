class Agendamento {
  final int id;
  final DateTime dataHorario;
  final String nameClient;
  final String phoneClient;
  final String status;

  Agendamento({
    required this.id,
    required this.dataHorario,
    required this.nameClient,
    required this.phoneClient,
    this.status = 'confirmado',
  });

  factory Agendamento.fromJson(Map<String, dynamic> json) {
    try {

          final dados = json['data'] ?? json;
      

      final rawDate = dados['data_horario'].toString();
      final parsedDate = rawDate.contains('T') 
          ? DateTime.parse(rawDate)
          : DateTime.parse('$rawDate:00');

      return Agendamento(
        id: dados['id'] as int,
        dataHorario: parsedDate,
        nameClient: dados['name_client'] as String,
        phoneClient: dados['phone_client'].toString(),
        status: dados['status'] as String? ?? 'confirmado',
      );
    } catch (e, stack) {
      print('Erro ao converter Agendamento: $e');
      print('Stack trace: $stack');
      print('Dados recebidos: $json');
      rethrow;
    }
  }
}