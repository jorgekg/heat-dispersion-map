import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class CompanyService {

  constructor(
    private httpClient: HttpClient
  ) { }

  public create(company: Company) {
    return this.httpClient.post<Company>(`${environment.api}/companies`, company);
  }
}

export class Company {
  id?: number;
  companyName?: string;
  socialName?: string;
}
