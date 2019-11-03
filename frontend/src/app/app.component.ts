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
      ['masculino', 1],
      ['Feminino', 1]
    ],
    options: { 
      title: 'Gênero',
      backgroundColor: '#000000',
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
      ['Happy', 1],
      ['Neutral', 1],
      ['Sad', 1]
    ],
    options: { 
      title: 'Expressões',
      backgroundColor: '#000000',
      animation:{
        duration: 1000,
        easing: 'out',
        startup: true
      }
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
          title: 'Gênero',
          backgroundColor: '#000000',
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
          title: 'Expressões',
          backgroundColor: '#000000',
          animation:{
            duration: 1000,
            easing: 'out',
            startup: true
          }
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
    }
  }
}
