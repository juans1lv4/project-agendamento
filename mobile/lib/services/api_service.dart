import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:intl/intl.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:mobile/model/agendamento.dart';

class ApiService {
  static final String _baseUrl = dotenv.get('BASE_URL');

  // Método para login
  static Future<bool> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl/login'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode({'email': email, 'password': password}),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('token', data['token']);
        return true;
      }
      return false;
    } catch (e) {
      print('Erro no login: $e');
      return false;
    }
  }

  // Método para obter horários disponíveis
  static Future<List<String>> getHorariosDisponiveis(String data) async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl/horarios-disponiveis?data=$data'),
      );

      if (response.statusCode == 200) {
        return List<String>.from(json.decode(response.body));
      }
      return [];
    } catch (e) {
      print('Erro ao buscar horários: $e');
      return [];
    }
  }

  // Método para criar agendamento (ATUALIZADO)
  static Future<Agendamento?> criarAgendamento({
    required DateTime data,
    required String horario, // Formato "HH:mm"
    required String nameClient,
    required String phoneClient,
  }) async {
    try {
      final dataHoraFormatada =
          '${DateFormat('yyyy-MM-dd').format(data)} $horario';

      final response = await http.post(
        Uri.parse('$_baseUrl/agendamentos'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: json.encode({
          'data_horario': dataHoraFormatada,  
          'name_client': nameClient,
          'phone_client': phoneClient,
        }),
      );

      print('Status: ${response.statusCode}');
      print('Resposta: ${response.body}');

      if (response.statusCode == 201) {
        final responseData = json.decode(response.body);

        if (responseData['data'] != null) {
          return Agendamento.fromJson(responseData['data']);
        }
        throw Exception('Estrutura de resposta inválida');
      }
      throw Exception('Erro na requisição: ${response.statusCode}');
    } catch (e, stack) {
      print('Erro detalhado: $e');
      print('Stack trace: $stack');
      rethrow;
    }
  }

  // Método para obter agendamentos (admin)
static Future<List<Agendamento>> getAgendamentosAdmin() async {
  try {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.get(
      Uri.parse('$_baseUrl/admin/agendamentos'),
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    );

    print('Resposta da API: ${response.body}');
    print('Token usado: $token');

    if (response.statusCode == 200) {
      final List<dynamic> data = json.decode(response.body);

      final parsed = data.map((json) {
        final Map<String, dynamic> jsonCopy = Map.from(json);

        if (jsonCopy['data_horario'] is String) {
          String rawDate = jsonCopy['data_horario'];

          final List<String> partes = rawDate.split(':');
          if (partes.length >= 2) {
            rawDate = '${partes[0]}:${partes[1]}';
          }

          jsonCopy['data_horario'] = rawDate.replaceAll(' ', 'T');
        }

        return Agendamento.fromJson(jsonCopy);
      }).toList();

      return parsed;
    }
    throw Exception('Erro ${response.statusCode}');
  } catch (e) {
    print('Erro ao buscar agendamentos: $e');
    throw Exception('Falha ao carregar agendamentos: $e');
  }
}

  // Método para cancelar agendamento (admin)
  static Future<bool> cancelarAgendamento(int id) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('token');

      final response = await http.delete(
        Uri.parse('$_baseUrl/admin/agendamentos/$id/cancelar'),
        headers: {'Authorization': 'Bearer $token'},
      );

      return response.statusCode == 200;
    } catch (e) {
      print('Erro ao cancelar agendamento: $e');
      return false;
    }
  }
}
