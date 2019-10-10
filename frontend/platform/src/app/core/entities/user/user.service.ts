import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  constructor(
    private httpClient: HttpClient
  ) { }

  public create(user: User) {
    return this.httpClient.post<User>(`${environment.api}/users`, user);
  }

  public getUser(userId: number) {
    return this.httpClient.get<User>(`${environment.api}/users`, {
      params: {
        id: userId.toString()
      }
    });
  }

  public login(user: User) {
    return this.httpClient.post<Token>(`${environment.api}/login`, user);
  }
}

export class Token {
  token: string;
  userId: number;
}

export class User {
  id?: number;
  name?: string;
  email: string;
  document?: string;
  password: string;
  companyId?: number;
}
