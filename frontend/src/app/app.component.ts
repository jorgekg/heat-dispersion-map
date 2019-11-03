import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { GoogleChartInterface } from 'ng2-google-charts/google-charts-interfaces';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  title = 'frontend';

  public person = '...';
  public age = '...';
  public feedback = '...';
  public faces = '...';

  public gender: GoogleChartInterface = {
    chartType: 'PieChart',
    dataTable: [
      ['Genero', 'Percentual'],
      ['Carregando...', 1],
      ['Carregando...', 1]
    ],
    options: { 
      title: 'Carregando...',
      backgroundColor: '#000000',
      height: 300,
      animation:{
        duration: 1000,
        easing: 'out',
        startup: true
      },
      colors: ['#e0440e', '#007bff']
    },
  };

  public expression: GoogleChartInterface = {
    chartType: 'ColumnChart',
    dataTable: [
      ['Expressão', 'Percentual'],
      ['Carregando...', 1],
      ['Carregando...', 1],
      ['Carregando...', 1]
    ],
    options: { 
      title: 'Carregando...',
      backgroundColor: '#000000',
      height: 300,
      animation:{
        duration: 1000,
        easing: 'out',
        startup: true
      },
      colors: ['#e0440e', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6']
    },
  };

  public age_all: GoogleChartInterface = {
    chartType: 'ColumnChart',
    dataTable: [
      ['Idade', 'total'],
      ['Carregando...', 1],
      ['Carregando...', 1],
      ['Carregando...', 1],
      ['Carregando...', 1]
    ],
    options: { 
      title: 'Carregando...',
      backgroundColor: '#000000',
      height: 400,
      animation:{
        duration: 1000,
        easing: 'out',
        startup: true
      },
      colors: ['#e0440e']
    },
  };

  constructor(
    private http: HttpClient
  ) { }

  ngOnInit() {
    setInterval(() => {
      this.getFaces();
      this.getPerson();
      this.getAge();
      this.getFeedback();
      this.getGender();
      this.getExpressao();
      this.getAgeAll();
    }, 3000);
  }

  private async getPerson() {
    const person = await this.http.get('http://localhost:3000/person').toPromise() as any;
    if (person && person.count) {
      this.person = person.count;
    }
  }

  private async getFaces() {
    const faces = await this.http.get('http://localhost:3000/faces').toPromise() as any;
    if (faces && faces.count) {
      this.faces = faces.count;
    }
  }

  private async getAge() {
    const age = await this.http.get('http://localhost:3000/age').toPromise() as any;
    if (age && age.age) {
      this.age = age.age;
    }
  }

  private async getFeedback() {
    const feedback = await this.http.get('http://localhost:3000/feedback').toPromise() as any;
    if (feedback && feedback.expression) {
      this.feedback = feedback.expression;
    }
  }

  private async getGender() {
    const gender = await this.http.get('http://localhost:3000/gender').toPromise() as any;
    if (gender && gender.length > 0) {
      this.gender.dataTable = [];
      gender.forEach(gend => {
        this.gender.dataTable.push([gend.gender === 'male' ? 'Masculino' : 'Feminino' , gend.percent]);
      });
      this.gender.dataTable.unshift(['Genero', 'Perecentual']);
      this.gender = {
        chartType: 'PieChart',
        dataTable: this.gender.dataTable,
        options: { 
          title: 'Gêneros detectados',
          backgroundColor: '#000000',
          height: 300,
          animation:{
            duration: 1000,
            easing: 'out',
            startup: true
          },
          colors: ['#e0440e', '#007bff']
        },
      }
    }
  }

  private async getExpressao() {
    const expression = await this.http.get('http://localhost:3000/expression').toPromise() as any;
    if (expression && expression.length > 0) {
      this.expression.dataTable = [];
      expression.forEach(gend => {
        this.expression.dataTable.push([gend.expression , gend.percent]);
      });
      this.expression.dataTable.unshift(['Expressão', 'Perecentual']);
      this.expression = {
        chartType: 'ColumnChart',
        dataTable: this.expression.dataTable,
        options: { 
          title: 'Expressões detectadas',
          height: 300,
          backgroundColor: '#000000',
          animation:{
            duration: 1000,
            easing: 'out',
            startup: true
          },
          colors: ['#e0440e', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6']
        },
      }
    }
  }

  private async getAgeAll() {
    const age_all = await this.http.get('http://localhost:3000/age_all').toPromise() as any;
    if (age_all && age_all.length > 0) {
      this.age_all.dataTable = [];
      age_all.forEach(gend => {
        this.age_all.dataTable.push([gend.idade , gend.total]);
      });
      this.age_all.dataTable.unshift(['Idade', 'total']);
      this.age_all = {
        chartType: 'ColumnChart',
        dataTable: this.age_all.dataTable,
        options: { 
          title: 'Idades detectadas',
          height: 400,
          backgroundColor: '#000000',
          animation:{
            duration: 1000,
            easing: 'out',
            startup: true
          },
          colors: ['#e0440e', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6']
        },
      }
    }
  }

  public getFeedbackIcon(expression) {
    switch (expression) {
      case 'neutral':
        return 'fa-meh-blank';
      case 'happy':
        return 'fa-smile-beam';
      case 'surprised':
        return 'fa-surprise';
      case 'sad':
        return 'fa-frown';
      case 'fa-angry':
        return 'fa-angry';
      case 'disgusted':
        return 'fa-meh-rolling-eyes';
    }
  }
}
